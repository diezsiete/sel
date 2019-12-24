import $ from "jquery";
import Routing from "../router";

export default {
    _queue: [],
    _interval: null,

    init() {
        this._routeMessageQueue = Routing.generate('admin_scraper_message_queue');

        const ids = [];
        const $buttons = $('.scraper');
        $buttons.each((index, item) => {
            const $item = $(item);
            ids.push($item.data('id'));
            $item.click(ev => this._buttonClick($(ev.currentTarget)))
        });

        this._messageQueueState(ids, (hvId, queueName) => {
            if(queueName === 'default') {
                this._startInterval(hvId)
            }
        });
    },



    _buttonClick($button) {
        if($button.hasClass('btn-outline-danger') || ($button.hasClass('btn-outline-primary') && !$button.hasClass('spin'))) {
            const id = $button.data('id');
            this._buttonSetEstadoStyle(id, 1);
            const route = Routing.generate('admin_scraper_retry_failed_message', {'hvId': id});
            $.get( route, response => {
                for(const hvId in response) {
                    this._buttonSetEstadoStyle(hvId, response[hvId]);
                    this._startInterval(hvId);
                }
            });
        }
    },

    _messageQueueState(hvIds, callback) {
        $.post(this._routeMessageQueue, {ids: hvIds}, response => {
            for(const hvId in response) {
                this._buttonSetEstadoStyle(hvId, response[hvId]);
                if(callback) {
                    callback(hvId, response[hvId])
                }
            }
        })
    },

    _buttonSetEstadoStyle(hvId, estado) {
        const $button = $('.scraper[data-id="' + hvId + '"]');
        if(estado === 2) {
            $button.attr('class', "scraper btn btn-outline-success mr-3").find('i').attr('class', 'fas fa-upload')
        }
        else if(estado === 3) {
            $button.attr('class', "scraper btn btn-outline-danger mr-3").find('i').attr('class', 'fas fa-upload')
        }
        else if(estado === 4 || estado === 1) {
            $button.attr('class', "scraper btn btn-outline-primary mr-3 spin").find('i').attr('class', 'fas fa-spinner fa-spin')
        }
    },

    _startInterval(hvId) {
        if (!this._queue.includes(hvId)) {
            this._queue.push(hvId);
            if(!this._interval) {
                this._interval = setInterval(() => {
                    if(this._queue.length > 0) {
                        this._messageQueueState(this._queue, (hvId, estado) => {
                            if (estado !== 1 && estado !== 4) {
                                const index = this._queue.indexOf(hvId);
                                if (index !== -1) this._queue.splice(index, 1);
                            }
                        })
                    } else {
                        clearInterval(this._interval);
                        this._interval = null
                    }
                }, 2000)
            }
        }
    }
};