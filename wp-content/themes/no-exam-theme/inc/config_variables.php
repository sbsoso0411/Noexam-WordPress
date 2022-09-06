<?php
$crm_client = new \BaseCRM\Client(['accessToken' => '415a17e2b4cc1f6d537fb131397cedaf3058f1ce27db20cb4812e2641685c2ab']);
$CRM_DEAL_STAGE_IDS = [
    'QUOTE_PRESENTED' => 5755269,
    'PARTIAL_APPLICATION' => 5755270,
    'APPLICATION_TAKEN' => 5763351,
    'APPROVED' => 5755273,
    'UNQUALIFIED' => 5755274,
    'LOST' => 5755275,
];


$CRM_USERS = [
    'Heidi' => [
        'id' => 987721,
        'email' => 'hblaser@noexam.com',
        'name' => 'Heidi Blaser',
        'phone' => '503-318-5886',
        'fname' => 'Heidi',
        'lname' => 'Blaser',
        'start_time_str' => '16:00 pm',
        'end_time_str' => '23:59 pm',
        'enabled' => true
    ],
    'Melissa' => [
        'id' => 992647,
        'email' => 'melissa@noexam.com',
        'name' => 'MELISSA SCHREUR',
        'phone' => '727-259-9999',
        'fname' => 'MELISSA',
        'lname' => 'SCHREUR',
        'start_time_str' => '12:00 am',
        'end_time_str' => '15:59 pm',
        'enabled' => false
    ],
    'Chris' => [
        'id' => 1006497,
        'email' => 'c.olson@noexam.com',
        'name' => 'Chris Olson',
        'phone' => '208-744-5072',
        'fname' => 'Chris',
        'lname' => 'Olson',
        'start_time_str' => '10:00 am',
        'end_time_str' => '15:59 pm',
        'states' => ['AL', 'AZ', 'AR', 'CA', 'CO', 'FL', 'GA', 'ID', 'IL', 'IN', 'IA', 'KS',
            'KY', 'LA', 'ME', 'MD', 'MI', 'MS', 'MO', 'NV', 'NJ', 'NM', 'NC', 'ND', 'OH',
            'OK', 'OR', 'PA', 'SC', 'TN', 'TX', 'UT', 'VA', 'WA', 'WV', 'WI'],
        'enabled' => false
    ],
    'Tom' => [
        'id' => 991671,
        'email' => 't.redding@noexam.com',
        'name' => 'Tom Redding',
        'phone' => '360-926-4566',
        'fname' => 'Tom',
        'lname' => 'Redding',
        'start_time_str' => '11:00 am',
        'end_time_str' => '15:59 pm',
        'states' => ['CA', 'CO', 'FL', 'ID', 'IL', 'IA', 'LA', 'ME', 'MI', 'OH',
            'SC', 'TX', 'WA'],
        'enabled' => true
    ],
    'John' => [
        'id' => 986733,
        'email' => 'jholloway@noexam.com',
        'name' => 'John Holloway',
        'phone' => '888-407-0714',
        'fname' => 'John',
        'lname' => 'Holloway',
        'enabled' => true
    ]
];

// john is administrator


$CRM_USER_LOST_REASON_IDS = [
    'KILL_QUESTIONS' => 2164344
];

$DROP_BOX_API_URL ='https://content.dropboxapi.com/2/files/upload'; //dropbox api url
$DROP_BOX_API_TOKEN = 'VYIkgrA_CBAAAAAAAAAAE9lCd0AA0uK5M6ANvCLiwyEjoz5-GYhyLglY_iH-zyl7'; // oauth token


$DROPBOX_FOLDER = 'NoExam'; // incompleted information folder
$DROPBOX_COMPLETED_FOLDER = 'NoExamApps'; //information completed folder

$MANDRILL_USERNAME = 'jholloway@noexam.com';
$MANDRILL_PWD = '7loO6gfFpNDpQIsUlghzdA';

//email subject line variables
$EMAIL_DEAL_COMPLETE_SUBJECT = 'Application Received';
$EMAIL_DEAL_CREATED_SUBJECT_PREFIX =': Your life insurance quotes';

$STATES_TIMEZONES = [
    'WA' => 'Pacific',
    'OR' => 'Pacific',
    'CA' => 'Pacific',
    'NV'=> 'Pacific',
    'MT' => 'Mountain',
    'ID' => 'Mountain',
    'WY' => 'Mountain',
    'UT' => 'Mountain',
    'CO' => 'Mountain',
    'NM' => 'Mountain',
    'ND' => 'Central',
    'SD'=> 'Central',
    'NE'=> 'Central',
    'KS'=> 'Central',
    'OK' => 'Central',
    'TX' => 'Central',
    'MN'=> 'Central',
    'IA'=> 'Central',
    'MO'=> 'Central',
    'AR'=> 'Central',
    'LA'=> 'Central',
    'WI'=> 'Central',
    'IL'=> 'Central',
    'KY'=> 'Central',
    'TN'=> 'Central',
    'AL'=> 'Central',
    'MS'=> 'Central',
    'ME' => 'Eastern',
    'NH'=> 'Eastern',
    'VT' => 'Eastern',
    'MA' => 'Eastern',
    'RI' => 'Eastern',
    'CT' => 'Eastern',
    'NY' => 'Eastern',
    'NJ' => 'Eastern',
    'PA' => 'Eastern',
    'MI' => 'Eastern',
    'OH' => 'Eastern',
    'IN' => 'Eastern',
    'WV' => 'Eastern',
    'VA' => 'Eastern',
    'NC' => 'Eastern',
    'SC' => 'Eastern',
    'GA' => 'Eastern',
    'FL' => 'Eastern',
    'DE' => 'Eastern'
];

//disallowed insurance company according to states
$DISALLOWED_STATES = [
    'sagicor' => ['AK', 'CT', 'ME', 'MT', 'NY', 'VT'],
    'sbli' => ['NY', 'MT', 'CA', 'PA', 'AL', 'GA', 'IL', 'NJ', 'NC', 'SC', 'OH', 'IA', 'MN', 'FL', 'TX', 'MI', 'AZ', 'NV', 'AR', 'MD', 'LA', 'MA', 'CO', 'MO', 'WI', 'WA', 'OR', 'VA', 'UT', 'RI', 'IN', 'WV', 'KY', 'TN', 'NH', 'MS'],
    'na' => ['AL', 'CT', 'ME', 'MT', 'CA', 'PA', 'NY']
];