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
    {{ 'Create new menu item'|trans }}
{% endblock %}

{% block body %}

    <script type="text/javascript">
        var checkUrlType = function(){
            var value = $('input[name="url_type"]:checked').val();
            if (value == undefined || value == 0 ){
                $('#url').parent().parent().show();
                $('#page').parent().parent().hide();
            }
            else{
                $('#url').parent().parent().hide();
                $('#page').parent().parent().show();
            }
        }
        $(document).ready(function() {
            $('input[name="url_type"]').click(function(){
                checkUrlType();
            });
            checkUrlType();
        });
    </script>

    {% if created is defined %}
        <script type="text/javascript">

            addItem({{ created.getId() }}, '{{ created.getTitle() }}');
            $('#modal').modal('hide');

        </script>
    {% else %}

        {{ form.render() }}

    {% endif %}
{% endblock %}