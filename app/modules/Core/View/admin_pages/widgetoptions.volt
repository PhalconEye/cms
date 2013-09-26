{#
   PhalconEye

   LICENSE

   This source file is subject to the new BSD license that is bundled
   with this package in the file LICENSE.txt.

   If you did not receive a copy of the license and are unable to
   obtain it through the world-wide-web, please send an email
   to phalconeye@gmail.com so we can send you a copy immediately.
#}

{% extends "layouts/modal.volt" %}

{% block title %}
    {{ name|trans }}
{% endblock %}

{% block body %}

    {% if widget_index is defined %}
        <script type="text/javascript">

            setEditedWidgetIndex({{widget_index}});
            $('#modal').modal('hide');

        </script>
    {% else %}

    {{ form.render() }}

    {% endif %}
{% endblock %}