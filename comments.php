<?php
//I love modalbox. I must do something crazy like this.
echo "<div id=\"disqus_thread\" onmouseover=\"Modalbox.resizeToContent()\"><script type=\"text/javascript\"> 
    /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
    var disqus_shortname = 'kwpolska'; // required: replace example with your forum shortname
 
    // The following are highly recommended additional parameters. Remove the slashes in front to use.
    var disqus_identifier = '".$_GET['id']."';
    var disqus_url = 'http://kwpolska.co.cc".$_GET['id']."/';
 
    /* * * DON'T EDIT BELOW THIS LINE * * */
    (function() {
        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
        dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
    })();
</script> <noscript>Please enable JavaScript to view the <a href=\"http://disqus.com/?ref_noscript\">comments powered by Disqus.</a></noscript></div>
Comments under this post are brought to you by <a href=\"http://disqus.com\" class=\"dsq-brlink\"><strong>DISQUS</strong></a>.
";
?>
