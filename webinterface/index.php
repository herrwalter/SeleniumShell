<?php


$dir = dirname(__DIR__);
chdir('C:\SeleniumTests\SeleniumShell\core');

require_once('Process.php');


$options = getopt('', array('env:', 'host:', 'port:', 'project:', 'browsers:', 'testsuite:', 'session:', 'ignore-solo-run'));

$ssOptions = '';
foreach ($_GET as $key => $value) {
    if (!$value) {
        $value = 'true';
    }
    $ssOptions .= '--ss-' . $key . ' ';
    $ssOptions .= '"' . $value . '" ';
}
$project = '';
if( isset($options['project']) ){
    $project = $options['project'];
}
$sessionName = trim(date('Y.m.d h.i.s', time()) . $project);

echo 'Executing SeleniumShell with options: ' . $ssOptions;
echo 'Sessionname=' . $sessionName;

exec('phpunit SeleniumShell.php ' .$ssOptions. ' --ss-print-tests true --ss-session "'.$sessionName. '"', $tests, $exitcode);

$i = 0;

function myFlush() {
    echo(str_repeat(' ', 256));
    if (@ob_get_contents()) {
        @ob_end_flush();
    }
    flush();
}

$processes = array();
$finishedProcesses = array();
$start = microtime(true);
$runningInstances = 0;

file_put_contents(dirname(__FILE__). DIRECTORY_SEPARATOR . 'progress.txt', '');
while (true) {
    $finished = 0;
    
    foreach($tests as $key => $test ){
        if( count($processes) < 30 ){
            $processes[] = array(
                'command' => new Process('phpunit --filter "' . $test . '" SeleniumShell.php --ss-session "'.$sessionName. '" ' . $ssOptions),
                'test' => $test 
                );
            unset($tests[$key]);
        }
    }
    
    foreach ($processes as $key => $process) {
        if (!$process['command']->isRunning()) {
            $finishedProcesses[] = $process;
            
            
            file_put_contents(dirname(__FILE__). DIRECTORY_SEPARATOR . 'progress.txt', 'Finished test: '. $process['test'] . '<br />', FILE_APPEND);
            
            
            unset($processes[$key]);
            $finished ++;
        }
    }
    
    
    if (empty($tests) && empty($processes)) {
        break;
    } else {
        $finished = 0;
    }
}
$totalTime = microtime(true) - $start;

$exitcode = 0;
foreach ($finishedProcesses as $process) {
    //var_dump(stream_get_contents($process->pipes[1]));
    //var_dump(stream_get_contents($process->pipes[0]));
    //var_dump($process->status);
    if($process['command']->getExitcode() !== 0){
        $exitcode = 1;
    }
}


$resultsPath = 'C:\\SeleniumTests\\SeleniumShell\\' . DIRECTORY_SEPARATOR . 'generated' . DIRECTORY_SEPARATOR . 'results' .DIRECTORY_SEPARATOR . $sessionName .DIRECTORY_SEPARATOR;
$results = file_get_contents($resultsPath . 'results.txt');
$errors = file_get_contents($resultsPath. 'errors.txt');
$failures = file_get_contents($resultsPath. 'failures.txt');
$incompletes = file_get_contents($resultsPath . 'incompletes.txt');
$skipped = file_get_contents($resultsPath . 'skipped.txt');

$extraInfo = array('Errors' => $errors,'Failures'=>$failures,'Incompletes' => $incompletes, 'Skipped' => $skipped);
        
echo $results . PHP_EOL;

foreach( $extraInfo as $message => $info ){
    if( $info !== '' ){
        echo PHP_EOL;
        echo $message . ': ' . PHP_EOL. PHP_EOL;
        echo $info . PHP_EOL;
    }
}

$runtimeInSeconds = number_format($totalTime, 2);
$minutes = floor($runtimeInSeconds / 60);
$seconds = $runtimeInSeconds % 60;
echo PHP_EOL . 'Total Runtime: ' . $minutes .' minutes '. $seconds . ' seconds' ; 
exit($exitcode);
