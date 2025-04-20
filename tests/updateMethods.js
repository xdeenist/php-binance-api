const source = './php-binance-api.php'
const dest = './generatedMethods.txt'
const fs = require('fs')
const path = require('path')

const fapiUrl = 'https://fapi.binance.com/fapi/'
const spotUrl = 'https://api.binance.com/api/'

const methodTemplate = /public function (\w+)\((.*?)\)/

function getMethodNameAndParams (signature = '') {
    const match = signature.match(methodTemplate)
    const methodName = match[1]
    const params = []
    const rawParams = match[2].split(',')
    for (const rawParam of rawParams) {
        const splitted = rawParam.split('=')
        const defaultValue = splitted[1] ? splitted[1].trim() : undefined
        const param = splitted[0].trim()
        const typeTemlate = /(\w+)\s+\$(\w+)/
        const typeMatch = param.match(typeTemlate)
        let type = undefined;
        let name = undefined;
        if (typeMatch) {
            type = typeMatch[1]
            name = typeMatch[2]
        } else {
            name = param.replace(/\$/g, '').trim()
        }
        if (name && name !== 'flags') {
            params.push({
                name,
                type,
                defaultValue
            })
        }
    }
    return {
        methodName,
        params
    }
}


function extractMethods(filePath) {
    const fileContent = fs.readFileSync(filePath, 'utf-8')
    const lines = fileContent.split('\n')
    const methods = []
    for (let i = 0; i < lines.length; i++) {
        const line = lines[i].trim()
        if (line.startsWith('public function') && line.includes('(')) {
            const method = getMethodNameAndParams(line)
            for (let j = i + 1; j < lines.length; j++) {
                const followingLine = lines[j].trim()
                const httpMatch = followingLine.match(/this->(httpRequest)\(('|")([^'"]+)('|"), ('|")([^'"]+)/)
                if (httpMatch) {
                    const url = httpMatch[3]
                    const baseUrl = method.methodName.includes('futures') ? fapiUrl : spotUrl
                    const methodType = httpMatch[6]
                    method.type = methodType
                    method.url = baseUrl + url
                    i = j
                    break
                } else if (followingLine.startsWith('public function')) {
                    i = j - 1
                    break
                }
            }
            methods.push(method)
        }
    }
    return methods
}

function writeStaticTestForMethod (method) {
    const methodName = method.methodName
    let result = 'public function test' + `${methodName.charAt(0).toUpperCase() + methodName.slice(1)}` + '()\n{\n    '
    const methodCall = writeMethodCall(method)
    result += writeTryCatch(methodCall)
    result += writeUrlCheck(method)
    result += writeParamsCheck(method.params)
    result += '}\n'
    console.log (method)
    return result
}

function writeMethodCall (method) {
    const methodName = method.methodName
    const params = method.params
    let paramStings = []
    for (const param of params) {
        const string = `$this->${param.name}`
        paramStings.push(string)
    }
    return `$this->binance->${methodName}(${paramStings.join(', ')});\n`
}

function writeTryCatch (methodCall) {
    return `try  {\n        ${methodCall}\n    } catch (\\Throwable $e) {\n\n    }\n`
}

function writeUrlCheck (method) {
    let url = method.url ? method.url : 'ADD_URL_HERE'
    if (method.type === 'POST' || method.params.length === 0) {
        return `    $this->assertEquals("${url}", self::$capturedUrl);\n`
    } else {
        let lines = '    $query = http_build_query([\n'
        const params = method.params
        for (const param of params) {
            const paramName = param.name
            lines += `        '${paramName}' => $this->${paramName},\n`
        }
        lines += '    ]);\n'
        lines += `    $endpoint = "${url}?" . $query;\n`
        lines += `    $this->assertEquals($endpoint, self::$capturedUrl);\n`
        lines += `    $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));\n`
        return lines
    }
}

function writeParamsCheck (params = []) {
    let result = ''
    if (params.length > 0) {
        result += '\n    parse_str(self::$capturedBody, $params);\n\n'
        for (const param of params) {
            const paramName = param.name
            result += `    $this->assertEquals($this->${paramName}, $params['${paramName}']);\n`
        }
    }
    return result
}

function getUniqueParams (methods) {
    const uniqueParams = []
    for (const method of methods) {
        for (const param of method.params) {
            let fullName = `${param.name}`
            if (param.type) {
                fullName += ` type: ${param.type}`
            }
            if (param.defaultValue) {
                fullName += ` default: ${param.defaultValue}`
            }
            uniqueParams.push(fullName)
        }
    }
    return new Set(uniqueParams)
}

(() => {
    const methods = extractMethods(source)
    const testCases = []
    for (const method of methods) {
        const testCase = writeStaticTestForMethod(method)
        testCases.push(testCase)
    }
    fs.writeFileSync(dest, testCases.join('\n'))
    console.log(`Generated test cases for ${methods.length} methods.`)
})()