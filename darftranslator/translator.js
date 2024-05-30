
const letterToIdMap = {
    'a': 25,
    'b': 24,
    'c': 23,
    'd': 22,
    'e': 21,
    'f': 20,
    'g': 19,
    'h': 18,
    'i': 17,
    'j': 16,
    'k': 15,
    'l': 14,
    'm': 13,
    'n': 12,
    'o': 11,
    'p': 10,
    'q': 9,
    'r': 8,
    's': 7,
    't': 6,
    'u': 5,
    'v': 4,
    'w': 3,
    'x': 2,
    'y': 1,
    'z': 0
  };
  
  const IDtoLetterMap = {
   25 : 'a',
   24 : 'b',
   23 : 'c',
   22 : 'd',
   21 : 'e',
   20 : 'f',
   19 : 'g',
   18 : 'h',
   17 : 'i',
   16 : 'j',
   15 : 'k',
   14 : 'l',
   13 : 'm',
   12 : 'n',
   11 : 'o',
   10 : 'p',
   9 : 'q',
   8 : 'r',
   7 : 's',
   6 : 't',
   5 : 'u',
   4 : 'v',
   3 : 'w',
   2 : 'x',
   1 : 'y',
   0 : 'z'
  };
  
  const darguments = {
    "0" : "darf",
    "1" : "darfield",
    "2" : "🐟"
  }
  
  const darguments2 = {
    "darf" : "0",   
    "darfield" : "1",
    "🐟" : "2"
  }
  
  
  function GetInput()
  {
  
  const text = document.getElementById("textinput").value; 
  
  return text;
  }
  
  function outputText(text)
  {
  
    const outputElement = document.getElementById("outputdiv");
    outputElement.innerHTML = text
  
  }
  
  function numbersToTrinary(string) {
    if (letterToIdMap[string]){
  
       
        return (letterToIdMap[string]).toString(3).toString();
    
        }
    else{
        if (string == "z"){
          console.log('string is z')
            return '0'
        }
        return string;
    }
  }
  
  
  
  
  function FilterWhiteSpaceStrings(elm){
  if(elm !== "" && elm !== " " && elm !== undefined) {
    return elm
  }
  }
  
  function toSymbols(numberArray) {
    
    const symbolArray = []
    for (let item in numberArray){
        if(darguments[numberArray[item]]){
       symbolArray.push(darguments[numberArray[item]])
        }
        else{symbolArray.push([numberArray[item]])};
   
    
  }
  
  return symbolArray;
  }
  
  function DargumentsToNumbers(dargumentstrings) {
  if (darguments2[dargumentstrings])
  {
  return darguments2[dargumentstrings]
  }
  else{
  
  }
  
  }
  
  function textToDarfish(inputText)
  {
  
  var translatedText = '';
  var processedText = inputText.toLowerCase()
  var processedText = processedText.replace(/(\r\n|\n|\r)/gm, "");
  
  if (/\d/.test(inputText)){
  return "Numbers are not supported"
  }
  
  var words = processedText.split(' ');
  words = words.filter(FilterWhiteSpaceStrings)
  const letters = words.map(word => word.split(''));
  const numbers = letters.map(word => word.map(letter => numbersToTrinary(letter)));
  
  numbers.forEach(function (wordArrays, index){
  
    wordArrays.forEach(function (item, index){
        
        const symbolsOfTheLetter = toSymbols(item.split(''));
        for (let symbol of symbolsOfTheLetter){
            translatedText = translatedText.concat(symbol," ")  
        }
        
        if (!(index === wordArrays.length -1)) {
        translatedText = translatedText.concat("🐱 ")};
      
    });
    if (!(index === numbers.length -1)) {
        translatedText = translatedText.concat("🐱🐱 ")};
  
  
  });
  
  
  
  
  
  
  return translatedText
  }
  
  function TrinaryToLetter(string)
  {
  var decimalNumber = parseInt(Number(string), 3);
  if (IDtoLetterMap[decimalNumber] && string.length > 0 )
    {
      return IDtoLetterMap[decimalNumber]
    } else {
      return string;
    }
  
  
  }
  
  
  
  
  function darfishToText(inputText)
  {
  
  if (/\d/.test(inputText)){
    return "Numbers are not supported"
  }
  
    var translatedText = ''
   
    var processedText = inputText.toLowerCase();
    processedText = processedText.replace(/(\r\n|\n|\r)/gm, "");
  
  
    var wordArrays = processedText.split('😺😺');
    var wordArrays = processedText.split('🐱🐱');
  
    wordArrays = wordArrays.filter(FilterWhiteSpaceStrings)
  
    var letterArrays = wordArrays.map(word => word.split('😺'));
    var letterArrays = wordArrays.map(word => word.split('🐱'));
  
    letterArrays = letterArrays.filter(FilterWhiteSpaceStrings)
  
    var symbolArrays = letterArrays.map(word => word.map(letter => letter.split(' ')));
    symbolArrays = symbolArrays.filter(FilterWhiteSpaceStrings)
  
    var numberArrays = symbolArrays.map(words => words.map(letter => letter.map(symbols => DargumentsToNumbers(symbols))));
  
    var combinedArray = numberArrays.map(words => words.map(letter => letter.join('')));
    combinedArray = combinedArray.map(words => words.map(letter => TrinaryToLetter(letter)));
    
    combinedArray.forEach(function (word, index){
  
      word.forEach(function(letter, index){
  
        translatedText = translatedText.concat(letter)
    
      })
  
        translatedText = translatedText.concat(" ");
  
  
    })
  
  
    return translatedText;
  
  }
  
  function onButtonPress()
  {
  var conversionType = document.getElementsByName("translation type")[0].value
  if (conversionType == 1){
  outputText(textToDarfish(GetInput()))
  }
  else {
  outputText(darfishToText(GetInput()))
  }
  
  }
  
  document.getElementById("outputdiv").onmousedown = function() {
    navigator.clipboard.writeText(document.getElementById("outputdiv").innerHTML)
  
  }