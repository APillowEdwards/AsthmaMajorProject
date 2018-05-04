
File structure:
    - mobile
        - Codebase for the mobile application, which should be built using the PhoneGap Build system, at http://build.phonegap.com/. The folder need only to be zipped up before upload.
    - rest
        - Codebase for the RESTful API.
    - web
        - Codebase for the human-web web application.
    - Asthma.apk
        - The built mobile app, that interfaces with 'http://andrewedwards.co.uk/asthma-final/rest/web', an instance of the submitted version of the RESTful API.
    - README.txt
        - This file

This project can be set-up from the zip file by performing the following actions:
    1. Upload the 'web' and 'rest' folders to an apache server with:
        * The 'mod_rewrite' module enabled.
        * PHP version 7 or greater
        * PHP extension 'pdo_mysql' enabled.
    2. Start a MySQL server instance and create a table within that instance
    3. Configure the two 'db.php' files at 'web/config/db.php' and 'rest/config/db.php', replacing the existing host and dbname values.
    4. Alter the "base_url" variable in 'mobile/js/main.js' to the url of the apache server, with '/rest/web/' added to the end.
    5. Compress the mobile folder into a zip file, and upload it to http://build.phonegap.com/. This might require the creation of an Adobe account.
    6. Download the resulting APK file, and install it to an Android device.

The project should now function correctly.

In order to create a user account, you should register on the login page of the web application.
An email will not be sent, but a mail file will be created in 'web/runtime/mail' that will contain a confirmation link.
It is recommended that you either create a user account with the username 'Andy' to use as an admin account, or alter the 'admins' attribute in 'web/config/web.php'.

A pre-compiled APK is included within the zip file, which is set-up to interact with the API at 'andrewedwards.co.uk/asthma-final/rest/web',
which is an instance of the submitted version of the web application and API.

A user account has been registered on this instance, accessible with the username 'mmp' and the password 'mmpmmp'


Licenses:

# # # # # # # # # # # #

Yii Framework is free software, released under the terms of the BSD license at the following URL:
https://www.yiiframework.com/license

Copyright © 2008-2018 by Yii Software LLC
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

    Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
    Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
    Neither the name of Yii Software LLC nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

# # # # # # # # # # # #

Adobe PhoneGap available for use under the terms set out at the following URL:
https://phonegap.com/about/license/

Of note are the 'Grant of Copyright License', 'Grant of Patent License' and 'Redistribution' section, defined as the following on 1st May 2018:
2. Grant of Copyright License
Subject to the terms and conditions of this License, each Contributor hereby grants to You a perpetual, worldwide, non-exclusive, no-charge, royalty-free, irrevocable copyright license to reproduce, prepare Derivative Works of, publicly display, publicly perform, sublicense, and distribute the Work and such Derivative Works in Source or Object form.

3. Grant of Patent License
Subject to the terms and conditions of this License, each Contributor hereby grants to You a perpetual, worldwide, non-exclusive, no-charge, royalty-free, irrevocable (except as stated in this section) patent license to make, have made, use, offer to sell, sell, import, and otherwise transfer the Work, where such license applies only to those patent claims licensable by such Contributor that are necessarily infringed by their Contribution(s) alone or by combination of their Contribution(s) with the Work to which such Contribution(s) was submitted. If You institute patent litigation against any entity (including a cross-claim or counterclaim in a lawsuit) alleging that the Work or a Contribution incorporated within the Work constitutes direct or contributory patent infringement, then any patent licenses granted to You under this License for that Work shall terminate as of the date such litigation is filed.

4. Redistribution
You may reproduce and distribute copies of the Work or Derivative Works thereof in any medium, with or without modifications, and in Source or Object form, provided that You meet the following conditions:
   *  You must give any other recipients of the Work or Derivative Works a copy of this License; and
   *  You must cause any modified files to carry prominent notices stating that You changed the files; and
   *  You must retain, in the Source form of any Derivative Works that You distribute, all copyright, patent, trademark, and attribution notices from the Source form of the Work, excluding those notices that do not pertain to any part of the Derivative Works; and
   *  If the Work includes a "NOTICE" text file as part of its distribution, then any Derivative Works that You distribute must include a readable copy of the attribution notices contained within such NOTICE file, excluding those notices that do not pertain to any part of the Derivative Works, in at least one of the following places: within a NOTICE text file distributed as part of the Derivative Works; within the Source form or documentation, if provided along with the Derivative Works; or, within a display generated by the Derivative Works, if and wherever such third-party notices normally appear. The contents of the NOTICE file are for informational purposes only and do not modify the License. You may add Your own attribution notices within Derivative Works that You distribute, alongside or as an addendum to the NOTICE text from the Work, provided that such additional attribution notices cannot be construed as modifying the License. You may add Your own copyright statement to Your modifications and may provide additional or different license terms and conditions for use, reproduction, or distribution of Your modifications, or for any such Derivative Works as a whole, provided Your use, reproduction, and distribution of the Work otherwise complies with the conditions stated in this License.

# # # # # # # # # # # #

Yii2-User module used under MIT licence from Dektrium.

Copyright (c) 2013-2016 Dektrium project

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.


# # # # # # # # # # # #

The jQuery library is used with implied permission thanks to the reference to the license in the source file.

"You are free to use the Project in any other project (even commercial projects) as long as the copyright header is left intact."

# # # # # # # # # # # #

HighCharts has been licensed for personal, non-commercial use under Creative Commons Attribution-NonCommercial 3.0.

# # # # # # # # # # # #
