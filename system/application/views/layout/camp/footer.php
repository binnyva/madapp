      
      </div>
<div class="sidebar" id="sidebar">
<!-- MODULE ENDS -->
</div>
     </div>
    </div>
    <!-- BODY ENDS -->
    <div class="footer">
     <div class="line"></div>
    </div>
</div>
<!--<a id="fdbk_tab" class="fdbk_tab_bottom" style="background-color:#222" href="https://getsatisfaction.com/mad/topics/new">FEEDBACK</a>-->

<?php 
$url = site_url(); 
if(((strpos($url, 'localhost') === false) and (strpos($url, '192.168') === false)) or 1) { // Don't show in local mode.
?>
<script>
  window.intercomSettings = {
    app_id: "xnngu157",
    name: $_SESSION['name'], // Full name
    email: $_SESSION['email'], // Email address
    // created_at: 0 // Signup date as a Unix timestamp
  };
  </script>
<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/xnngu157';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-5816278-6', 'auto');
  ga('send', 'pageview');
</script>
<?php } ?>
</body>
</html>
