<html>
<head>
  <style>
    .black-block{
    width: 100vw;
    position: relative;
    left: 50%;
    right: 50%;
    margin-left: -50vw;
    margin-right: -50vw;
  }
  .container{
      width: 80vw;
      margin: 0 auto;
  }
  body { background-color: yellow; font-family: -apple-system, BlinkMacSystemFont, sans-serif;}

  </style>
</head>
<div class="black-block" style="margin-top:10%;background:black;padding-top:10%;padding-bottom:10%">
  <div class="container">
    <p style="text-transform:uppercase;margin-bottom:20%;font-weight:800;color:#3186d2">FORTUNE</p>
    <h2 style="color:#fff"><?php echo `fortune`; ?></h2>        
  </div>
</div>
</html>
