{#
   PhalconEye

   LICENSE

   This source file is subject to the new BSD license that is bundled
   with this package in the file LICENSE.txt.

   If you did not receive a copy of the license and are unable to
   obtain it through the world-wide-web, please send an email
   to phalconeye@gmail.com so we can send you a copy immediately.
#}
{% extends "../../Core/View/layouts/widget.volt" %}

{% block content %}

<link href="{{baseUrl}}external/bxslider-4/jquery.bxslider.css" type="text/css" rel="stylesheet">

<ul class="bxslider" data-slider-id="{{ slider_id }}">
{% for slide in slides %}
    {% if height > 0 %}
    <li><div style="min-height: {{ height }}px">{{ slide }}</div></li>
    {% else %}
    <li>{{ slide }}</li>
    {% endif %}
{% endfor %}
</ul>

<script type="application/javascript">
document.addEventListener('DOMContentLoaded', function() {
  $('.bxslider').filter('[data-slider-id="{{ slider_id }}"]').bxSlider({
    pause: {{ params['duration'] }},
    speed: {{ params['speed'] }},
    auto: ({{ params['auto'] }} == 1),
    autoHover: ({{ params['auto_hover'] }} == 1),
    controls: ({{ params['controls'] }} == 1),
    video: ({{ params['video'] }} == 1),
    pager: ({{ params['pager'] }} == 1),
    adaptiveHeight: true,
    captions: true,
    nextText: "›",
    prevText : "‹"
  });
}, false);
</script>

{% endblock %}