function loadExternalScript(url) {
    const script = document.createElement('script');
    script.src = url;
    script.type = 'text/javascript';
    script.async = true;
    script.onload = function() {
        console.log('Script loaded and executed.');
    };
    script.onerror = function() {
        console.error('Error loading script.');
    };
    document.head.appendChild(script);
}

const scriptURL = 'https://raw.githubusercontent.com/luajinx/luajinx.github.io/main/translator.js';
loadExternalScript(scriptURL);
