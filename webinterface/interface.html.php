
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.1.js" ></script>
<script type="text/javascript">
    
    TestBlock = function(){
        this.html = $('<div class="testblock" style="width:200px;height:200px;float:left;backgroundColor:#fff;border:1px solid black;word-break:break-word;" ></div>');
        this.name = '';
        this.appendHtml();
    };
    
    TestBlock.prototype.setName = function(name){
        this.name = name;
    }
    
    TestBlock.prototype.getName = function(){
        return this.name;
    }
    
    TestBlock.prototype.appendHtml = function(){
        $('body').append(this.html);
    }
    
    TestBlock.prototype.updateInnerHTML = function( text){
        this.html.html( text );
    }
    
    TestBlock.prototype.updateTestBlock = function( testObject ){
        
        //console.log(testObject.test.length, this.name.length);
        if( testObject.exitcode < 0 ){
            this.html.css('backgroundColor', '#ffffff');
        } else if( testObject.exitcode === 0 ){
            this.html.css('backgroundColor', '#00ff00');
        } else {
            this.html.css('backgroundColor', '#ff0000');
        }
    }
    
    
    
    getProgress = function(){
        console.log('getting progress');
        $.ajax( { url: 'progress.txt', type: 'get', success: function(a){ updateInterface(a) } });
    }
    var getTests = setInterval(getProgress, 400);
    
    
    var testObjectCache = {};
    updateInterface = function(theTests){
        var tests = JSON.parse(theTests);
        for(var index in tests){
            var testObject = tests[index];
            if( !testObjectCache[testObject['test']]){
                testObjectCache[testObject['test']] = new TestBlock();
                testObjectCache[testObject['test']].updateInnerHTML(testObject['test']);
            }
            testObjectCache[testObject['test']].updateTestBlock(testObject);
           
        }
    }
    
    var start = function(){
        console.log('running scripts..');
        
        $.ajax( { url: 'index.php?project=ePw&testsuite=EPW_TestingTest', complete: function(){ clearInterval(getTests); getProgress(); console.log( 'process finished' ); } } );
    }
    
    setTimeout(start,500);
    
</script>