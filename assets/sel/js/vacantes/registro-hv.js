import './../../css/vacantes/registro.scss'
import { sessionMaintainer, clickOutsideAlert } from "./registro";
import './hv'
import $ from "jquery";


$(function(){
    clickOutsideAlert();
    sessionMaintainer();
});