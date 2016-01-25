<!DOCTYPE html>
<html class="st-layout ls-top-navbar ls-bottom-footer" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Connect with your friends in a whole new way!">
        <meta name="keywords" content="BlabAway">
        <title><?=$address?></title>
        <link href="//blabaway.com/template/themes/learning/css/vendor.min.css" rel="stylesheet">
        <link href="//blabaway.com/template/themes/learning/css/theme-core.min.css" rel="stylesheet">
        <link href="//blabaway.com/template/themes/learning/css/module-essentials.min.css" rel="stylesheet"/>
        <link href="//blabaway.com/template/themes/learning/css/module-layout.min.css" rel="stylesheet"/>
        <link href="//blabaway.com/template/themes/learning/css/module-sidebar.min.css" rel="stylesheet"/>
        <link href="//blabaway.com/template/themes/learning/css/module-sidebar-skins.min.css" rel="stylesheet"/>
        <link href="//blabaway.com/template/themes/learning/css/module-navbar.min.css" rel="stylesheet"/>
        <link href="//blabaway.com/template/themes/learning/css/module-chat.min.css" rel="stylesheet"/>
        <link href="//blabaway.com/template/themes/social-1/css/module-cover.min.css" rel="stylesheet"/>
        <link href="//blabaway.com/template/themes/social-1/css/module-timeline.min.css" rel="stylesheet"/>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.0/jquery.min.js'></script>
        <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
            <![endif]-->
    </head>
    <body class="" style="background: none;">
        <div class="st-container">
            <div class="navbar navbar-main navbar-primary navbar-fixed-top" role="navigation">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="/">MailDud</a>
                    </div>
                    <div class="collapse navbar-collapse" id="main-nav">
                        <!-- <ul class="nav navbar-nav">
                            <li class=""><a href="/content/view/blabaway-abuse-policy/4">Abuse</a></li>
                            <li class=""><a href="/content/view/terms-of-service/5">Terms</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="/log/in">Sign In</a></li>
                            <li><a href="/register">Sign Up</a></li>
                        </ul> -->
                    </div>
                </div>
            </div>
            <div class="st-pusher" id="content">
                <div class="st-content">
                    <div class="st-content-inner">
                        <div class="container"><div class="panel panel-default">
                                <div class="panel-heading"><h3 class="panel-title">MailDud | <?=$address?></h3></div>
                                <div style="margin: 10px;">
                                    <div class='row'>
                                        <div class='col-md-3'><strong>From</strong></div>
                                        <div class='col-md-5'><strong>Subject</strong></div>
                                        <div class='col-md-3'><strong>Time</strong></div>
                                        <div class='col-md-1'><strong>Action</strong></div>
                                    </div>
                                    <div id='messageContainer'></div>
                                    <div id='messageTemplate' style="display: none;">
                                        <div class='row'>
                                            <div class='col-md-3'>{from}</div>
                                            <div class='col-md-5'>{subject}</div>
                                            <div class='col-md-3'>{time}</div>
                                            <div class='col-md-1'>{action}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <strong>MailDud</strong> &copy; Copyright <?= date("Y") ?></footer>
    </div>
    <script>
        var lastMessage = 0;
        function mailPulse(){
            $.ajax({
                url: "/ajax/json/mail/getMessages?mailbox=<?=$address?>&last="+lastMessage,
                dataType: "json",
                success: function(data){
                    $.each(data, function(key, message){
                        template = $("#messageTemplate").html();
                        template = template.replace("{from}", message.from);
                        template = template.replace("{subject}", message.subject);
                        template = template.replace("{time}", message.timestamp);
                        template = template.replace("{action}", message.action);
                        lastMessage = message.id;
                        $("#messageContainer").append(template);
                    });
                }
            });
        }
        
        setInterval(function(){
            mailPulse();
        }, 5000);
        mailPulse();
    </script>
    <script>
        var colors = {
            "danger-color": "#e74c3c",
            "success-color": "#81b53e",
            "warning-color": "#f0ad4e",
            "inverse-color": "#2c3e50",
            "info-color": "#2d7cb5",
            "default-color": "#6e7882",
            "default-light-color": "#cfd9db",
            "purple-color": "#9D8AC7",
            "mustard-color": "#d4d171",
            "lightred-color": "#e15258",
            "body-bg": "#f6f6f6"
        };
        var config = {
            theme: "learning",
            skins: {
                "default": {
                    "primary-color": "#16ae9f"
                },
                "orange": {
                    "primary-color": "#e74c3c"
                },
                "blue": {
                    "primary-color": "#4687ce"
                },
                "purple": {
                    "primary-color": "#af86b9"
                },
                "brown": {
                    "primary-color": "#c3a961"
                }
            }
        };
    </script>
    <script src="//blabaway.com/template/themes/learning/js/vendor-core.min.js"></script>
    <script src="//blabaway.com/template/themes/learning/js/vendor-tables.min.js"></script>
    <script src="//blabaway.com/template/themes/learning/js/vendor-forms.min.js"></script>
    <script src="//blabaway.com/template/themes/learning/js/module-essentials.min.js"></script>
    <script src="//blabaway.com/template/themes/learning/js/module-layout.min.js"></script>
    <script src="//blabaway.com/template/themes/learning/js/module-sidebar.min.js"></script>
    <script src="//blabaway.com/template/themes/learning/js/module-chat.min.js"></script>
    <script src="//blabaway.com/template/themes/learning/js/theme-core.min.js"></script>
</body>
</html>