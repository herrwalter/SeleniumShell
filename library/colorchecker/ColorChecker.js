var ErrorBlock = function ( color ){
  this.color = color || null; // the color where it is all about
	this.elements = []; //array containing all the elements on which an error is found.
	this.html = document.createElement( 'div' );
	this.colorTitle = document.createElement( 'div' );
        this.colorTitle.className = 'wrongcolor';
	this.colorBlock = document.createElement( 'div' );
	this.errorBlock = document.createElement( 'div' );

	this.setColor( color );
	this.create();
}
/**
 * Creates the container 
 */
ErrorBlock.prototype.createHtml = function(){
	var style =	this.html.style
		style.width = '100%';
		style.minHeight = '120px';
		style.position = 'relative';
		style.borderBottom = '1px solid black';
}
/**
 * Creates the colorTitle 
 */
ErrorBlock.prototype.createColorTitle = function(){
	var style = this.colorTitle.style;
		style.width = '100%';
		style.marginRight = '-120px';
		style.height = '30px';
		style.lineHeight = '30px';
		style.fontSize = '24px';
		style.fontWeight = 'bold';
		style.color = '#000';
		style.cssFloat = 'left';
		style.styleFloat = 'left';
}
/**
 * Creates the colorBlock 
 */
ErrorBlock.prototype.createColorBlock = function(){
	var style = this.colorBlock.style;
		style.width = '100px';
		style.height = '100px';
		style.cssFloat = 'left';
		style.styleFloat = 'left';
		style.backgroundColor = '#fff';
		style.border = '2px solid #fff';
		style.margin = '8px';
}

/**
 * Creates the html 
 */
ErrorBlock.prototype.create = function(){
	this.createHtml();
	this.createColorTitle();
	this.createColorBlock();
	
	this.html.appendChild( this.colorBlock );
	this.html.appendChild( this.colorTitle );
	this.html.appendChild( this.errorBlock );
}
/**
 * Sets the color of errors 
 * @param {String} color Rgb color
 */
ErrorBlock.prototype.setColor = function( color ){
	if( this.color !== null ){
		this.colorBlock.style.backgroundColor = color;
		this.colorTitle.innerHTML = color;
		return
	}
	throw new Error( 'Error color allready set' );
}
/**
 *  Add an error to the error block
 */
ErrorBlock.prototype.addError = function( error ){
	var message = document.createElement('span');
		message.innerHTML =  error.element.tagName + ( error.element.id ? '#' + error.element.id : '' ) + ( error.element.className ? '.' + error.element.className : ''  ) + ' on ' + error.property;
	
	//set reference if element isn't allready refered
	this.elements.indexOf( error.element ) == -1 ? this.elements.push( error.element ): false;
	this.errorBlock.appendChild( message );
	this.errorBlock.appendChild( document.createElement('br') );
}
/**
 * Makes sure we have an error block for every color. 
 * Also adds the errors and colors
 */
var ErrorBlockFactory = function(){
	//singleton
	if( this === this.instance ){
		return this.instance;
	}
	ErrorBlockFactory.prototype.instance = this;
}
/**
 * Retruns the error block by color 
 * @return {ErrorBlock}
 */
ErrorBlockFactory.prototype.getErrorBlock = function( errorObject ){
	var color = errorObject.color;
	var errorBlock = null;
	
	if( !this.allBlocks[color] ){
		errorBlock = new ErrorBlock( color );
		this.allBlocks[color] = errorBlock;
	}
	return this.allBlocks[color];	
}
/**
 * @var {Object} allBlocks with all blockError instances
 */
ErrorBlockFactory.prototype.allBlocks = {};

/**
 * Creates the interface or view for the user. 
 */
var Interface = function(){
	//singleton
	if( this.instance === this){
		return this.intance;
	}
	this.create();
	Interface.prototype.instance = this;
}
Interface.prototype.wrapper = document.createElement( 'div' );
Interface.prototype.container = document.createElement( 'div' );
Interface.prototype.logo = document.createElement('div');
Interface.prototype.progressBar = document.createElement('div');
Interface.prototype.feedbackArea = document.createElement( 'div' );
/**
 * Create the big container 
 */
Interface.prototype.createContainer = function (){
	var style = this.container.style;
	style.width = '30%';
	style.height = '100%';
	style.right = '0px';
	style.top = '0px';
	style.position = 'absolute';
	style.backgroundColor = '#444';
}
/**
 * Create the wrapper..  
 */
Interface.prototype.createWrapper = function (){
	var style = this.wrapper.style;
	style.width = '100%';
	style.height = '100%';
	style.position = 'absolute';
	style.display = 'none';
	style.zIndex = 99999999;
	style.left = '0px';
	style.top = '0px';
	style.backgroundColor = 'rgba( 0,0,0,0.5 )';
	
	this.wrapper.id = 'wrapper';
}
/**
 * Create the logo (just a title) 
 */
Interface.prototype.createLogo = function (){
	var style = this.logo.style;
	style.height = '50px';
	style.backgroundColor = '#222';
	style.color = '#fff';
	
	//append the name
	this.logo.innerHTML = '<h1>Color Checker</h1>';
}
/**
 * Creates the progress bar 
 */
Interface.prototype.createProgressBar = function(){
        this.progressBar.id = 'progressbar';
	var style = this.progressBar.style;
	style.height = '40px';
	style.width = '0%';
	style.backgroundColor = '#BADA55';
}
/**
 * The feedback area where we append the ErrorBlocks on
 */
Interface.prototype.createFeedbackArea = function (){
	var style = this.feedbackArea.style;
	style.backgroundColor = '#fff';
}
/**
 * Create the html instance
 */
Interface.prototype.create = function(){
	this.createWrapper();
	this.createContainer();
	this.createLogo();
	this.createProgressBar();
	this.createFeedbackArea();
	
	this.container.appendChild( this.logo );
	this.container.appendChild( this.progressBar );
	this.container.appendChild( this.feedbackArea );
	this.wrapper.appendChild( this.container );

	document.body.appendChild( this.wrapper );
}
/**
 * Updates the progressbar.
 * 
 * @param {Number} integer 100 or smaller (percentage)
 */
Interface.prototype.updateProgressBar = function ( percentage ){
	if( percentage <= 100 ){
		this.progressBar.style.width = percentage + '%';
		this.progressBar.innerHTML = percentage + '%';
	}
}
/**
 * Updates the wrappers height to the height of the document. 
 */
Interface.prototype.updateHeight = function(){
	this.wrapper.style.height = document.height + 'px';
}
/**
 * Update errors  
 * @param {Array} with errorObjects
 */
Interface.prototype.updateErrors = function( errors ){
	var factory = new ErrorBlockFactory();
	for( var i = 0; i < errors.length; i++){
		var errorBlock = factory.getErrorBlock( errors[i] );
		errorBlock.addError( errors[i] );
		errorBlock.setColor( errors[i].color );
		this.updateHeight();
		this.feedbackArea.appendChild( errorBlock.html );
	}
}

/**
 * Show interface 
 */
Interface.prototype.show = function (){
	this.wrapper.style.display = 'block';
}
/**
 * Hide interface 
 */
Interface.prototype.hide = function (){
	this.wrapper.style.display = 'none';
}

var ColorChecker = function( allowedColors ){
	this.setAllTagNames();
	this.setColorProperties();
	this.setAllowedColors( allowedColors );
	this.setAllElements();
	this.findColors();
	this.overlay.show();
}
/**
 * @var {Array} with the allowed colors provided by the user. 
 */
ColorChecker.prototype.allowedColors = [];

/**
 * @var {Array} with all the elements of the body document. 
 */
ColorChecker.prototype.allElements = [];
/**
 * @var {Array} with all the possible colorProperties 
 */
ColorChecker.prototype.colorProperties = [];
/**
 * Takes a dashed-string and converts the to camelCase.
 * So camel-case will convert to camelCase. 
 * @param {String} string
 * @return {String} 
 */

/**
 * @var {Interface} instance of a prototyped Interface. 
 */
ColorChecker.prototype.overlay = new Interface();
/**
 * @var {Array} allErrors with all found Errors; 
 */
ColorChecker.prototype.allErrors = [];
/**
 * Get all tag names 
 */
ColorChecker.prototype.allTagNames = [];
/**
 * Gets all the tagnames existing in the body. and sets the allTagNames Array 
 */
ColorChecker.prototype.setAllTagNames = function(){
	var elements = document.querySelectorAll('body *');
	for( i in elements ){
		var tagName = elements[i].tagName;
		if( ColorChecker.prototype.allTagNames.indexOf( tagName ) < 0 && tagName !== undefined ){
			ColorChecker.prototype.allTagNames.push(tagName);
		}
	}
}

/**
 *Matches everything between and with 'rgb(' and ')'
 * @param {String} string
 */
ColorChecker.prototype.matchAllRgbCodesInString = function( string ){
	return string.match(/rgb\((.*?)\)/g )
}
/**
 * Gets a dashed string and returns a string in camelCase. 
 *
 * @param {String} string 
 * @return {String} camelCasedString
 */
ColorChecker.prototype.dashToCamel = function( string ){
	return string.replace(/-([a-z])/g, function (g) { return g[1].toUpperCase() });
}
/**
 * Finds all posible color properties in the dom by looping of every display type and find any property with the word 'color' in it.
 */
ColorChecker.prototype.setColorProperties = function(){
	for( i in this.allTagNames ){
		var element = document.createElement( this.allTagNames[i] );	
		for( property in element.style ){
			/* check if the property has the word Color or color in it. */
			if( property.indexOf( 'color' ) !== -1 || property.indexOf( 'Color' ) !== -1 ){
				this.colorProperties.indexOf( property ) == -1 ? this.colorProperties.push( property ) : false;
			}
		}
	}
}


/**
 * Sets the color properties that are given by the user and converts the colors the RGB Array format. 
 * @param {Array} colors
 */
ColorChecker.prototype.setAllowedColors = function( colors ){
	for( var i = 0; i < colors.length; i++ ){
		var rgbArray = this.createRgbArray( colors[i] );
		this.allowedColors.push( rgbArray  );
	}
}
/**
 * Set allElements  
 */
ColorChecker.prototype.setAllElements = function(){
	var allElements = document.querySelectorAll( 'body *:not(br):not(script)' );
	var nrOfElements = allElements.length;
	var i = 0;
	var newArray = [];
	while( i < nrOfElements ){
		this.allElements.push( allElements[i] );
		i++;
	}
}
/**
 * Finds any color on any element in the dom. Then matches it.
 */
ColorChecker.prototype.findColors = function (){
	// get the element to check
	var element = this.allElements.shift();
	
	// check if it' s not empty
	if( element ){
		// loop over every color property to check on the element.
		for( var i = 0; i < this.colorProperties.length; i++ ){
			var colorProperty = this.colorProperties[i];
			style = window.getComputedStyle( element );
			var potentialColor = style[ colorProperty ];
			potentialColor.indexOf( 'rgb(' ) !== -1 ? this.matchColor( element, this.colorProperties[i] ) : false;
		}
		if( this.allElements.length >= 0 ){
			var totalElements = document.querySelectorAll( 'body *' ).length;
			var currentElement = -( this.allElements.length - totalElements);
			var elementCount = currentElement / totalElements *100;
			this.overlay.updateProgressBar( parseInt( elementCount ) );
			setTimeout( function() {
				ColorChecker.prototype.findColors();
			}, 10 );
			if( this.allElements.length == 0){
				this.overlay.updateErrors( this.allErrors );
			}
		}
	}
	
}
/**
 * Converts hexadecimal code to rgb array that we use in this Object 
 * @param {String} hex
 * @return {Array}
 */ 
ColorChecker.prototype._hexToRgbArray = function( hex ){
	if( hex.length == 7 && hex.charAt( 0 ) == "#" ){
		var r = parseInt( hex.substring( 1,3 ), 16 );
		var g = parseInt( hex.substring( 3,5 ), 16 );
		var b = parseInt( hex.substring( 5,7 ), 16 );
	}
	else{
		var r = parseInt( hex.substring( 1, 2) + hex.substring( 1,2), 16 );
		var g = parseInt( hex.substring( 2, 3) + hex.substring( 2,3), 16 );
		var b = parseInt( hex.substring( 3, 4) + hex.substring( 3,4), 16 );
	}
	return [r,g,b];
}
/**
 * Converts an rgb string to rgb array that we use 
 * @param {String} rgb
 * @return {Array}
 */
ColorChecker.prototype._rgbToRgbArray = function( rgb ){
	return rgb.match( /[0-9]+/g );
}

/**
 *
 * This functions Checks what helper function to use to return an RGB array. 
 * @param {String} color
 * @return {Array}
 */
ColorChecker.prototype.createRgbArray = function( color ){
	if( color[0] == "#" ){
		return this._hexToRgbArray( color );
	}
	else{
		return this._rgbToRgbArray( color );
	}
}

/**
 * Checks if the found color-property on the element has a valid color
 * according to the allowedColors.
 * 
 * Outputs unallowed colors.
 *  
 * @param {Element} element
 * @param {String} styleProperty
 */
ColorChecker.prototype.matchColor = function( element, styleProperty ){
	var elementStyle = window.getComputedStyle( element );
	var allRgbCodesInString = this.matchAllRgbCodesInString( elementStyle[styleProperty] );
	for( var i = 0; i < allRgbCodesInString.length; i++ ){
		var rgbArray = this.createRgbArray( allRgbCodesInString[i] );
		var valid = false;
			for( var i = 0; i < this.allowedColors.length; i++ ){
				var allowedColor = this.allowedColors[i];
				if( rgbArray.join( '' ) === allowedColor.join( '' ) 
					){
					return true;
				}
			}
		var error = {
			color : 'rgb(' + rgbArray[0] + ', '+ rgbArray[1] + ', ' + rgbArray[2] + ')',
			element : element,
			property : styleProperty
		}
		this.allErrors.push( error );
	}
}