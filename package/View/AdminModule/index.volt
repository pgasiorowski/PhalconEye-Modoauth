{#
 +------------------------------------------------------------------------+
 | OAuth Module for PhalconEye CMS                                        |
 +------------------------------------------------------------------------+
 | Copyright (c) 2014 Piotr Gasiorowski (p.gasiorowski@vipserv.org)       |
 | License: MIT                                                           |
 +------------------------------------------------------------------------+
 | Author: Piotr Gasiorowski <p.gasiorowski@vipserv.org>                  |
 +------------------------------------------------------------------------+
#}

{% extends "Core/View/layouts/admin.volt" %}

{% block title %}{{ "Module OAuth settings"|i18n }}{% endblock %}

{% block content %}
    <div class="span12">
        <div class="row-fluid">
            {{ form.render() }}
        </div>
        <!--/row-->
    </div><!--/span-->

{% endblock %}
