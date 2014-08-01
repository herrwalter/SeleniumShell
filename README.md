SeleniumShell
=============

Small framework on Selenium 2 with php


Usage
=============

Run test of your project
```
selenium-shell -project [projectName]
```

Run test of your project against certain browsers
```
selenium-shell -project [projectName] -browsers "chrome, firefox"
```

Run tests of a sub directory in your project
```
selenium-shell -project [projectName] -subpath "/subdir"
```

Run tests of a testsuite
```
selenium-shell -project [projectName] -testsuite [TestSuiteName]
```

Run screenshots comparisons of last 2 runs
```
selenium-shell compare-screenshots -project [projectName]
```

Run setup before project run
```
selenium-shell -project [projectName] --setup-before-project
```

Update your selenium drivers and standalone server
```
selenium-shell update
```

Print testsnames of project
```
selenium-shell -project [projectName] --print-tests
```

Run test against host/port comibation
```
selenium-shell -project [projectName] -port 4444 -host 127.0.0.1
```

Run a maximum of parallel tests
```
selenium-shell -project [projectName] -max-sessions 20
```

Test Annotations
=============

Run only one test
```
/**
 * @ss-solo-run true
 */
 public function testSomething(){
 
 }
 ```
 
Run test only against certain browser(s)
```
/**
 * @ss-solo-run chrome
 */
 public function testSomething(){
 
 }
 ```

