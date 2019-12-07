import $ from 'jquery';
import scraperRetryFailedMessage from './../../../component/scraper-retry-failed-message'

$(function(){
    $('.datatable').on( 'draw.dt', function () {
        scraperRetryFailedMessage.init()
    });
});