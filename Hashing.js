//Import bcrypt
var bcrypt = require('bcryptjs');
//Instead of "B4c0/\/" a password is passed to the function for hashing and salting
bcrypt.genSalt(10, function(err, salt) {
    bcrypt.hash("B4c0/\/", salt, function(err, hash) {
        // Store hash in your password DB.
        //
    });
});

//other  option in PHP
//passes $password and hashes it with bcrypt, the array is machine performance cost, 10 is the default and is low expense
//$hash = password_hash($password, PASSWORD_BCRYPT, array('cost'=>11));

//This set verifies the password
/*if (password_verify($password, $hash)) {
	if(password_needs_rehash($hash, PASSWORD_DEFAULT, $options)) {
		$newHash = password_hash($password, PASSWORD_DEFAULT, $options);
	}
}
*\