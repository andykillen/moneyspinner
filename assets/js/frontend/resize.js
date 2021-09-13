const resizer = {
    // timer for setTimeout
    timer : false,
    // default iframe providers that are looked for
    iframeProviders : ['youtube.com','youtu.be','vimeo.com','youtube-nocookie.com'],
    // initialization script
    init:function(){
        resizer.appendExtraProviders();
        resizer.iframes();
        // attach event listener to resize event.
        window.addEventListener("resize", function (){
            clearTimeout(resizer.timer );
            resizer.timer = setTimeout(function(){
                resizer.iframes();
            }, 200);
        } , false);
    },
    // check if variable exists and append extra video providers if needed
    appendExtraProviders: function(){
        if (typeof window.theme !== 'undefined' && window.theme.hasOwnProperty('iframeproviders')) {
            resizer.iframeProviders = [...resizer.iframeProviders, ...window.theme.iframeProviders.split(",")];
        }
    },
    // loop thought iframs looking for ones to re-size
    iframes: function(){
        let iframes = document.getElementsByTagName('iframe');
        // if no iframes ignore.
        if(iframes == null){
            return;
        }

        iframes.map( (elm) => {
            // exclude iframes by class 'exclude' 
            if( elm.classList.contains("exclude") ){
                return elm;
            }
            // inner loop to check for valid urls
            resizer.iframeProviders.map( (provider) => {
                if( elm.getAttribute("src").indexOf(provider) > 4 ){
                    let w = elm.parentElement.clientWidth;
                    elm.style.width= w +"px";
                    elm.style.height= (w/16 *9) +"px";
                }
            });
            
        });
    }
}

resizer.init();
