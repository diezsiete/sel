(function($) {

    'use strict';



    /*
    Wizard #3
    */
    var $w3finish = $('#w3').find('ul.pager li.finish');
        // $w3validator = $("#w3 form").validate({
        //     highlight: function(element) {
        //         $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        //     },
        //     success: function(element) {
        //         $(element).closest('.form-group').removeClass('has-error');
        //         $(element).remove();
        //     },
        //     errorPlacement: function( error, element ) {
        //         element.parent().append( error );
        //     }
        // });

    $w3finish.on('click', function( ev ) {
        ev.preventDefault();
        var validated = $('#w3 form').valid();
        if ( validated ) {
            new PNotify({
                title: 'Congratulations',
                text: 'You completed the wizard form.',
                type: 'custom',
                addclass: 'notification-success',
                icon: 'fas fa-check'
            });
        }
    });

    $('#w3').bootstrapWizard({
        tabClass: 'wizard-steps',
        nextSelector: 'ul.pager li.next',
        previousSelector: 'ul.pager li.previous',
        firstSelector: null,
        lastSelector: null,
        onNext: function( tab, navigation, index, newindex ) {
            var validated = $('#w3 form').valid();
            if( !validated ) {
                $w3validator.focusInvalid();
                return false;
            }
        },
        onTabClick: function( tab, navigation, index, newindex ) {
            if ( newindex == index + 1 ) {
                return this.onNext( tab, navigation, index, newindex);
            } else if ( newindex > index + 1 ) {
                return false;
            } else {
                return true;
            }
        },
        onTabChange: function( tab, navigation, index, newindex ) {
            var $total = navigation.find('li').length - 1;
            $w3finish[ newindex != $total ? 'addClass' : 'removeClass' ]( 'hidden' );
            $('#w3').find(this.nextSelector)[ newindex == $total ? 'addClass' : 'removeClass' ]( 'hidden' );
        },
        onTabShow: function( tab, navigation, index ) {
            var $total = navigation.find('li').length - 1;
            var $current = index;
            var $percent = Math.floor(( $current / $total ) * 100);

            navigation.find('li').removeClass('active');
            navigation.find('li').eq( $current ).addClass('active');

            $('#w3').find('.progress-indicator').css({ 'width': $percent + '%' });
            tab.prevAll().addClass('completed');
            tab.nextAll().removeClass('completed');
        }
    });


}).apply(this, [jQuery]);