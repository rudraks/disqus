var disqus_config = function () {literal}{{/literal}
this.page.remote_auth_s3 = "{$disqus_config.message} {$disqus_config.hmac} {$disqus_config.timestamp}";
this.page.api_key = "{$disqus_config.api_key}";
{literal}};
(function () {  // REQUIRED CONFIGURATION VARIABLE: EDIT THE SHORTNAME BELOW
var d = document, s = d.createElement('script');
s.src = {/literal}'//{$disqus_config.shortname}.disqus.com/embed.js'; {literal} // IMPORTANT: Replace EXAMPLE with your forum shortname!
s.setAttribute('data-timestamp', +new Date());
(d.head || d.body).appendChild(s);
})();
{/literal}