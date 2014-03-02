<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=UTF-8" http-equiv="content-type">
        <title><?php echo $heading; ?></title>
        <style type="text/css">

            ::selection{ background-color: #E13300; color: white; }
            ::moz-selection{ background-color: #E13300; color: white; }
            ::webkit-selection{ background-color: #E13300; color: white; }

            body {
                background-color: #fff;
                margin: 40px;
                font: 13px/20px normal Helvetica, Arial, sans-serif;
                color: #000;
            }

            a {
                color: #003399;
                background-color: transparent;
                font-weight: normal;
            }

            h1 {
                color: #79BBFF;
                background-color: transparent;
                border-bottom: 1px solid #D0D0D0;
                font-size: 19px;
                font-weight: normal;
                margin: 0 0 14px 0;
                padding: 14px 15px 10px 15px;
            }

            code {
                font-family: Consolas, Monaco, Courier New, Courier, monospace;
                font-size: 12px;
                background-color: #f9f9f9;
                border: 1px solid #D0D0D0;
                color: #002166;
                display: block;
                margin: 14px 0 14px 0;
                padding: 12px 10px 12px 10px;
            }
            p {
                margin: 12px 15px 12px 15px;
            }
        </style>
    </head>
    <body>
        <div style="margin: 10px;
             border: 1px solid #D0D0D0;
             -webkit-box-shadow: 0 0 8px #D0D0D0;
             width: 480px;
             ">
            <h1><?php echo $heading; ?></h1>
            <?php echo $message; ?>

        </div>
        <div style="margin: 10px;
             color: #888888;
             -webkit-box-shadow: 0 0 8px #888888;
             width: 480px;
             ">
                 <?php echo $footer; ?>
        </div>
    </body>
</html>