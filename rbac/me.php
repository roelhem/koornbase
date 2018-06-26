<?php

Rbac::group('me:', function() {


    Rbac::ability('view-me')->assignTo('ActiveUser');


});