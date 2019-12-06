import $ from 'jquery';
import Routing from './../../../router';

const routeMessageQueue = Routing.generate('admin_scraper_message_queue');

const queue = [];
let interval = null;

function startInterval(hvId) {
    if (!queue.includes(hvId)) {
        queue.push(hvId);
        if(!interval) {
            let interval = setInterval(function () {
                if(queue.length > 0) {
                    messageQueueState(queue, (hvId, queueName) => {
                        if (queueName !== 'default') {
                            const index = queue.indexOf(hvId);
                            if (index !== -1) queue.splice(index, 1);
                        }
                    })
                } else {
                    clearInterval(interval);
                    interval = null
                }
            }, 2000)
        }
    }
}

function buttonSetQueueStyle(hvId, $queueName) {
    const $button = $('.scraper[data-id="' + hvId + '"]');
    if($queueName === "success") {
        $button.attr('class', "scraper btn btn-outline-success mr-3").find('i').attr('class', 'fas fa-upload')
    }
    else if($queueName === "failed") {
        $button.attr('class', "scraper btn btn-outline-danger mr-3").find('i').attr('class', 'fas fa-upload')
    }
    else if($queueName === "default") {
        $button.attr('class', "scraper btn btn-outline-primary mr-3").find('i').attr('class', 'fas fa-spinner fa-spin')
    }
}

function buttonClick($button) {
    if($button.hasClass('btn-outline-danger')) {
        const id = $button.data('id');
        buttonSetQueueStyle(id, 'default');
        const route = Routing.generate('admin_scraper_retry_failed_message', {'hvId': id});
        $.get( route, response => {
            for(const hvId in response) {
                buttonSetQueueStyle(hvId, response[hvId]);
                startInterval(hvId);
            }
        });
    }
}

function messageQueueState(hvIds, callback) {
    $.post(routeMessageQueue, {ids: hvIds}, response => {
        for(const hvId in response) {
            buttonSetQueueStyle(hvId, response[hvId]);
            if(callback) {
                callback(hvId, response[hvId])
            }
        }
    })
}

$(function(){
    $('.datatable').on( 'draw.dt', function () {
        const ids = [];
        const $buttons = $('.scraper');
        $buttons.each((index, item) => {
            const $item = $(item);
            ids.push($item.data('id'));
            $item.click(ev => buttonClick($(ev.currentTarget)))
        });

        messageQueueState(ids);

    });
});