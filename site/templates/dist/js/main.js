import $ from 'jquery';
import 'jquery-migrate/dist/jquery-migrate.min';
import '@popperjs/core/dist/cjs/popper-base';
import 'bootstrap/dist/js/bootstrap.min';
import sayHello from "./components/sayHello";

sayHello("Hello");
$('.test').text('Render from Jquery')