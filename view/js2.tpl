{literal}
(function(){
  function injectScript(src) {
      var script = document.createElement('script'); script.src = src; script.type = 'text/javascript';document.body.appendChild(script);
  }
  function getCookie(name) {
    var value = "; " + document.cookie;
    var parts = value.split("; " + name + "=");
    if (parts.length == 2) return parts.pop().split(";").shift();
  }
  if(document.cookie.indexOf('disqus_HMAC') > -1 ) {
      injectScript("/api/disqus/"+getCookie('disqus_HMAC')+"/config.js2");
  }
})();
{/literal}