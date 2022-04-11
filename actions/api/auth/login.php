<?php

$response_code = 400;
$return = [
    'status'  => 'failed',
    'message' => 'authentication failed',
    'data'    => [],
];

if(request() == 'GET')
{
    http_response_code($response_code);
    echo json_encode(['message'=>'This method is not allowed. Use POST instead']);
    die();
}
// manual validation
// username is required
if(!isset($_POST['username']) || empty($_POST['username']))
{
    $return['message'] = 'username required and cannot empty';
}

// password is required
else if(!isset($_POST['password']) || empty($_POST['password']))
{
    $return['message'] = 'password required and cannot empty';
}
else

{
    $conn  = conn();
    $db    = new Database($conn);

    $user = $db->single('users',[
        'username' => $_POST['username'],
        'password' => md5($_POST['password']),
    ]);

    if($user)
    {
        $response_code     = 200;
        $return['status']  = 'success';
        $return['message'] = 'authentication success';
        $return['data']    = JwtAuth::generate([
            'id'       => $user->id,
            'username' => $user->username,
            'roles'    => get_roles($user->id),
            'allowed'  => get_allowed_routes($user->id),
        ]);
    }
}

http_response_code($response_code);
echo json_encode($return);
die();