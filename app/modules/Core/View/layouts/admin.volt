{#
   PhalconEye

   LICENSE

   This source file is subject to the new BSD license that is bundled
   with this package in the file LICENSE.txt.

   If you did not receive a copy of the license and are unable to
   obtain it through the world-wide-web, please send an email
   to phalconeye@gmail.com so we can send you a copy immediately.
#}

{# admin.volt #}
<!DOCTYPE html>
<html>
<head>
    <title>{% block title %}{% endblock %}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {% block head %}

    {% endblock %}

</head>
<body>


<div class="navbar navbar_panel">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="{{ url("admin") }}"><img alt="Phalcon Eye"
                                                            src="{{ url('assets/img/core/pe_logo_white.png') }}"/></a>

            <div class="nav-collapse collapse">
                {{ headerNavigation.render() }}
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>

    <div class="navbar-text">
        <a href="{{ url() }}" class="btn btn-primary">{{ 'Back to site' | trans }}</a>
        <a href="{{ url("logout") }}" class="btn btn-danger">{{ 'Logout' | trans }}</a>
    </div>
</div>

<div class="content">

    {% block header -%}
    {%- endblock %}

    <div class="row-fluid row-after-header">
        <div>
            {{ content() | trans }}
            {{ flashSession.output() | trans }}
        </div>
    </div>

    <div class="row-fluid">
        <!--/row-->
        {%- block content -%}
        {%- endblock %}
    </div>
    <!--/row-->
</div>

<div id="footer">
    Phalcon Eye v.<?php echo PE_VERSION ?> <br/>[{{ date('d-m-Y H:i:s') }}]
</div>

{{ assets.outputCss() }}
{{ assets.outputJs() }}

</body>
</html>