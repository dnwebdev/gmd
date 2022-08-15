/**
 * Created by kayiz on 29/09/16.
 */

function getIP(json) {
    console.log("My public IP address is: ", json.ip);
    console.log("Parent: ", window.parent.location.origin);
}
function init(classIndex) {
    var headTag = document.getElementsByTagName("head")[0];
    var jqTag = document.createElement('script');
    jqTag.type = 'text/javascript';
    jqTag.src = 'https://api.ipify.org?format=jsonp&callback=getIP';
    headTag.appendChild(jqTag);
    var wrap = document.createElement('div');
    var frButton = document.createElement('button');
    if (classIndex !== undefined) {
        var a = document.getElementsByClassName('gomodoEmbed')[classIndex];
    } else {
        var a = document.getElementById('gomodoEmbed');
    }
    var dF = document.createElement('div'); // <- Background dark
    var FullWrap = document.createElement('iframe'); // <- Iframe
    var close = document.createElement('div'); // <- Close button
    var loader = document.createElement('span');

    var height = 'auto';
    if (a.hasAttribute('data-height')){
        height = parseInt(a.getAttribute('data-height'))+'px';
    }
    FullWrap.setAttribute('is',"x-frame-bypass")
    a.style.width = '100%';
    a.style.cursor='default';
    a.style.margin='0 auto';
    a.style.height = 'auto';
    a.style.textDecoration = 'none';
    a.style.position = 'relative';
    a.innerHTML="";
    // a.style.overflow = 'hidden';
    a.style.lineHeight = '1px';
    frButton.style.cursor = 'pointer';
    a.style.display = 'inline-block';
    frButton.innerHTML='Book With Gomodo';
    if (a.hasAttribute('data-title')){
        frButton.innerHTML=a.getAttribute('data-title');
    }
    frButton.style.boxShadow='0 5px 11px 0 rgba(0,0,0,.18), 0 4px 15px 0 rgba(0,0,0,.15)';
    frButton.style.width = 'auto';
    frButton.style.color = '#fff';
    frButton.style.fontSize = '14px';
    frButton.style.borderRadius='3px';
    frButton.style.maxWidth = '250px';
    frButton.style.display = 'block';
    frButton.style.padding = '10px 15px';
    frButton.style.backgroundColor = 'rgb(66, 133, 244)';
    frButton.style.border = 'none';
    if (a.hasAttribute('data-background')){
        frButton.style.backgroundColor = a.getAttribute('data-background');
    }
    if (a.hasAttribute('data-color')){
        frButton.style.color = a.getAttribute('data-color');
    }
    wrap.style.opacity = 0;
    wrap.style.position = 'absolute';
    wrap.style.left = 0;
    wrap.style.top = 0;
    wrap.style.height = height;
    frButton.style.margin = '0 auto';
    if (a.hasAttribute('data-align')){
        if(a.getAttribute('data-align') == 'left'){
            margin = '0 auto 0 0';
        } else if (a.getAttribute('data-align') == 'right'){
            margin = '0 0 0 auto';
        } else {
            margin = '0 auto';
        }
        frButton.style.margin = margin;
    }
    wrap.style.width = '100%';

    // Loader
    loader.innerHTML = 'Loading...';
    loader.style.fontFamily = 'sans-serif';
    loader.style.color = 'white';
    loader.style.position = 'absolute';
    loader.style.top = '50%';
    loader.style.left = '50%';
    loader.style.transform = 'translate(-50%, -50%)';

    // frButton.setAttribute('src', a.getAttribute('data-image'));
    frButton.setAttribute('crossorigin', 'anonymous');
    a.appendChild(frButton);
    a.appendChild(wrap);

    frButton.addEventListener('click', pop_up, false);

    close.addEventListener('click', dismiss, false);

    // close when click outside iframe
    document.addEventListener('click', function(e) {
        if (e.target === dF && e.target !== FullWrap) {
            dismiss()
        }
    }, false)

    function pop_up(e) {
        var body = document.body,
            html = document.documentElement,
            h = document.body.clientHeight;
        var height = Math.max( body.scrollHeight, body.offsetHeight,
            html.clientHeight, html.scrollHeight, html.offsetHeight );
        e.returnValue = false;

        // default close button
        close.style.position = 'fixed';
        close.style.width = '30px';
        close.style.height = '30px';
        close.style.zIndex = 3002;
        close.style.cursor = 'pointer';

        // full wrap after resize
        FullWrap.style.position = 'fixed';
        FullWrap.style.overflow = 'auto';
        FullWrap.style.zIndex = 3001;

        FullWrap.style.backgroundColor = 'none transparent';
        FullWrap.style.border = 'none';
        FullWrap.setAttribute('src',a.getAttribute('data-url') + '?widget=1');
        // FullWrap.setAttribute('crossorigin', 'anonymous');
        dF.style.backgroundColor = '#808080d4';
        dF.style.zIndex = 30000;
        dF.style.width = '100%';
        // dF.style.height = height+'px';
        dF.style.height = '100vh';
        dF.style.position = 'fixed';
        dF.style.left = 0;
        dF.style.top = 0;
        dF.appendChild(loader)
        dF.appendChild(FullWrap);
        dF.appendChild(close);
        document.body.appendChild(dF);
        // document.body.appendChild(close);
        close.innerHTML='<button id="closeBtn">&#10006</button>';
        
        var closeBtn = document.getElementById('closeBtn');
        closeBtn.style.borderRadius = '50%';
        closeBtn.style.width = '32px';
        closeBtn.style.height = '32px';
        closeBtn.style.fontWeight = 'bold';
        closeBtn.style.backgroundColor = '#3d99f6';
        closeBtn.style.color = 'white';
        closeBtn.style.border = '3px solid white';
        closeBtn.style.cursor = 'pointer';
        closeBtn.style.padding = '4px';
        closeBtn.style.fontSize = '15px';
        resize();
        return false;
    }

    function dismiss() {
        document.body.removeChild(dF);
        return false;
    }

    function resize() {
        var body = document.body,
            html = document.documentElement,
            h = document.body.clientHeight;
        var height = Math.max( body.scrollHeight, body.offsetHeight,
            html.clientHeight, html.scrollHeight, html.offsetHeight );
        var FullWrap_height = Math.max(FullWrap.offsetHeight, FullWrap.clientHeight);

        if (html.clientWidth > 590) { // <- Mobile version
            FullWrap.style.width = '576px';
            FullWrap.style.height = '650px';
            FullWrap.style.top = '50%';
            FullWrap.style.bottom = '0px';
            FullWrap.style.left = '50%';
            FullWrap.style.transform = 'translate(-50%,-50%)';
            FullWrap.style.borderRadius = '5px 0 0 5px';
            FullWrap.style.maxHeight = '90%';
            
            close.style.right = '50%';
            close.style.top = '50%';
            close.style.transform = 'translateX(50%) translateX(286px) translateY(-50%) translateY(-325px)';

            if (FullWrap_height !== 0 && FullWrap_height < 650) {
                close.style.transform = 'unset';
                close.style.top = '3%';
                close.style.transform = 'translateX(50%) translateX(286px)';
            }
        } else { // <- Dekstop version
            if (height < 650) {
                FullWrap.style.width = '100%';
                FullWrap.style.height = '100%';
                FullWrap.style.maxHeight = 'unset';
                FullWrap.style.top = '0px';
                FullWrap.style.left = '0%';
                FullWrap.style.transform = 'unset';
                FullWrap.style.borderRadius = '0';
                
                close.style.transform = 'unset';
                close.style.right = '0';
                close.style.top = '0';
            } else {
                FullWrap.style.width = 'calc(100% - 10px)';
                FullWrap.style.height = '550px';
                FullWrap.style.top = '50%';
                FullWrap.style.left = '50%';
                FullWrap.style.transform = 'translate(-50%, -50%)';
                FullWrap.style.borderRadius = '5px 0 0 5px';

                close.style.right = '5px';
                close.style.top = '50%';
                close.style.transform = 'translateY(-50%) translateY(-275px)';
            }
            FullWrap.style.bottom = '0px';
        }
    }

    window.addEventListener('resize', function(event){
        resize();
    });

    resize();
}

var embedClassLength = document.querySelectorAll('.gomodoEmbed').length
var embedIdLength = document.querySelectorAll('#gomodoEmbed').length

if (embedClassLength !== 0) {
    for ( i = 0; i < embedClassLength; i++) {
        init(i);
    }
} 
if (embedIdLength !== 0) {
    init()
}