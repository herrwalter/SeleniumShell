SeleniumShell
=============

Small framework on Selenium 2 with php.

Will run tests in parallel by default.

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

Show screenshots results after comparison
```
selenium-shell compare-screenshots -project [projectName] -email your@email.com -differance [sliced/highlighted]
```

List possible screenshot comparison projects
```
selenium-shell compare-screenshots --printProjects
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

Run serial
```
selenium-shell -project [projectName] -max-sessions 1
```

Run tests againt a certain environment
```
selenium-shell -project [projectName] -env acceptance
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
 * @ss-browsers chrome
 */
 public function testSomething(){
 
 }
 ```

Run setup-before-project test
```
/**
 * @ss-setup-before-project
 */
 public function testSomething(){
 
 }
```

TestHelpers
=============

ColorTests
==
```
class MyColorTest extends SeleniumShell_ColorCheckerTest
{
   public function urlProvider()
   {
      return array(
         'www.google.nl',
         'www.yahoo.com' 
      );
      
   }
   
   
   public function getAllowedColors()
   {
      return array(
          '#000',
          'rgb(255,255,255)',
          '#BADA44'
      );
   }
}

```


FormHandler
=====
```
$formHandler = new SeleniumShell_FormHandler($session);
$formHandler->mapValuesToElementsById(
   array(
      'fieldId' => 'the new value'
      'someSelectbox' => 'value or "random"'
      'checkbox' => true // selected
      'elementId' => 'click'
   )
);
$formHandler->submitForm();
```

ScreenshotHandler
=====
```
$screenshotHandler = new SeleniumShell_ScreenshotHandler( $session, $path, $prefix );
$screenshotHandler->makeScreenshot( $name );
```


URLHandler
=====
```
class MyUrlHandler extends SeleniumShell_Abstract_URLHandler
{
   protected function getProjectConfigPath(){
      return 'path/to/config.ini';
   }
   
   /**
    * NOTE: [environment] correspondse with -env parameter or setted default env in either core config or -env on cli
    */
   protected function getBaseUrls(){
      return array(
         '[environment]' => 'http://testing.google.nl'
      );
   }
   
   protected function getUris(){
      return array(
         'login' => '/login',
         'search' => '/search'
      );
   }
}

$urlHandler = new MyUrlHandler();
$urlHanlder->getUrl('login'); // http://testing.google.nl/login
```

MailInbox
=====
```
// expects imap
$mailInbox = SeleniumShell_MailInbox($host, $username, $password);
$inboxes = $mailInbox->getMailboxes();
$unreadMails = $mailInbox->getUnreadEmails();
```


CSVReader 
=====
```
$cvsReader = new SeleniumShell_CSVReader($fileName, $delimiter);
$csvReader->getCsvData();
$csvReader->getHeaders();
```
