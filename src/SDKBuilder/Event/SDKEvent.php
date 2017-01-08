<?php

namespace SDKBuilder\Event;

class SDKEvent
{
    CONST ADD_PROCESSORS_EVENT = 'sdk.add_processors';
    CONST PRE_PROCESS_REQUEST_EVENT = 'sdk.pre_process_request';
    CONST POST_PROCESS_REQUEST_EVENT = 'sdk.post_process_request';
    CONST PRE_SEND_REQUEST_EVENT = 'sdk.pre_send_request_event';
    CONST POST_SEND_REQUEST_EVENT = 'sdk.post_send_request_event';
}