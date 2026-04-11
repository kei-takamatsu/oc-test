const { GoogleGenAI } = require('@google/genai');
try {
  const genAI = new GoogleGenAI('dummy_key');
  console.log('Instance created');
  if (genAI.getGenerativeModel) {
    console.log('getGenerativeModel exists');
  } else {
    console.log('getGenerativeModel DOES NOT exist');
    console.log('Available props:', Object.keys(genAI));
    console.log('Prototype props:', Object.keys(Object.getPrototypeOf(genAI)));
  }
} catch (e) {
  console.error(e);
}
