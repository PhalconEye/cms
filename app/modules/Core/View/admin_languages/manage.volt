{#
   PhalconEye

   LICENSE

   This source file is subject to the new BSD license that is bundled
   with this package in the file LICENSE.txt.

   If you did not receive a copy of the license and are unable to
   obtain it through the world-wide-web, please send an email
   to phalconeye@gmail.com so we can send you a copy immediately.
#}

{% extends "layouts/admin.volt" %}

{% block title %}{{ "Manage language"|trans }}{% endblock %}

{% block head %}
    <script type="text/javascript">
        var deleteItem = function (id) {
            if (confirm('{{ "Are you really want to delete this translation?" | trans}}')) {
                window.location.href = '{{ url(['for':'admin-languages-delete-item'])}}' + id + '?lang={{ lang.id }}{% if search is defined %}&search={{ search }}{% endif %}';
            }
        }

        var requestAddItem = function () {
            var url = '{{ url(['for':'admin-languages-create-item'])}}';
            var data = {
                'language_id': {{ lang.id }}
            };

            PE.modal.open(url, data);
        }

        var editItem = function (id) {
            var url = '{{ url(['for':'admin-languages-edit-item'])}}' + id;
            var data = {
                'id':id,
                'language_id': {{ lang.id }}
            };

            PE.modal.open(url, data);
        }
    </script>
{% endblock %}

{% block header %}
    <div class="navbar navbar-header">
        <div class="navbar-inner">
            {{ navigation.render() }}
        </div>
    </div>
{% endblock %}


{% block content %}

    <div class="span12">
        <div class="language_manage_header">
            <h3><a href="{{ url(['for': 'admin-languages']) }}">{{ "Languages" | trans }}</a>
                > {{ "Manage language" | trans }}
                "{{ lang.name }}"</h3>
            <button class="btn btn-primary" onclick='requestAddItem();'>{{ 'Add new item'|trans }}</button>
            <form class="navbar-search pull-right" method="GET" action="{{ url(['for': 'admin-languages-manage'])~lang.id }}">
                {% if search is defined %}
                <div class="icon-remove" onclick="window.location.href='{{ url(['for': 'admin-languages-manage'])~lang.id }}'"></div>
                {% endif %}
                <input name="search" type="text" class="search-query" placeholder="{{ 'Search' |trans }}" value="{{ search }}"/>
                <div class="icon-search" onclick="$(this).parent().submit();"></div>
            </form>
        </div>
        <div class="language_manage_body">
            <table class="table">
                <thead>
                <tr>
                    <th>{{ 'Original' | trans }}</th>
                    <th>{{ 'Translated' | trans }}</th>
                    <th>{{ 'Options' | trans }}</th>
                </tr>
                </thead>
                <tbody>
                {% for item in paginator.items %}
                    <tr>
                        <td>
                            {{ item.original }}
                        </td>
                        <td>
                            {{ item.translated }}
                        </td>
                        <td>
                            {{ link_to(null, 'Edit' | trans, "onclick" : 'editItem(' ~ item.id ~ ');return false;') }}
                            {{ link_to(null, 'Delete' | trans, "onclick": 'deleteItem('~ item.id ~');return false;') }}
                        </td>
                    </tr>
                {% endfor %}
                </tbody>
            </table>
            {{ partial("paginator") }}
        </div>
    </div>



{% endblock %}
