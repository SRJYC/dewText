This is an application for proof of concept.
The goal is to show dew computing.

This app allows you to create some text files and save it in local and cloud.
The local text content will be synchronized with cloud.
It will automatically synchronize every 10 seconds. But you can click button to make it synchronize.
Restore option will make local be a copy of cloud. It's used when local content is lost.

To use this,
put the dewText folder in xampp/htdocs
then enter url: http://localhost/dewText/ in your browser

Cloud server uses my aws virtual machine. It will be expired in Oct 2019.
The url is http://ec2-18-224-251-211.us-east-2.compute.amazonaws.com/dewText/
There's no file management and security.
Everything on the cloud can be see and modified by anyone.

Or you can use your own cloud server.
Then you have to going to following files and change all urls.
index.php line 66, 67
synchDew.php line 201

If you want use this kind of service, you probably can find something more useful and safe.

Rock Chen
University of PEI
11.19.2018