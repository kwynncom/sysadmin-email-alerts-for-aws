# sysadmin-email-alerts-for-aws
Email alerts for disk usage, AWS EC2 CPU balance, and Ubuntu updates

2020

09 (September) - all times are EDT / GMT -4 / Atlanta / New York

02

1:20am

I have not been clear until now whether hrtime() is monotonic forever or only for a given boot session.  It would appear to be the latter.  That is, 
if you reboot your machine, you can no longer use hrtime(1) output to sort by.  

**************
2020/08/07 7:27pm EDT / GMT -4

This has been running on a cron job and emailing for about a week.  Everything seems to work.  

This also checks for outgoing network traffic.  The emails were being trigged too often for this.  I am getting 
usage over 30 minutes that equated to 40 GB / month.  I just set the trigger limit to 50.  That would be $4.50 if it 
continued all month.  See "Data Transfer" charges: https://aws.amazon.com/ec2/pricing/on-demand/


**********
I am just starting, but the very basics work.  I'm not emailing yet, but I'm gathering the data.

This uses / will use 3 previous projects / files:

https://github.com/kwynncom/aws-ec2-metrics-web-display

https://github.com/kwynncom/ubuntu-update-web-checker

https://github.com/kwynncom/kwynn-php-general-utils/blob/master/email.php

***********
07/29 7:39pm

2 more deploy entries:

update aws cpu
update ubuup


2020/07/28 7:49pm - deploy checklist:

update /opt/kwynn
create / add cred for email
add all paths
create and run tests

*****
That dratted origin command, again:

git remote set-url origin git@github.com:kwynncom/sysadmin-email-alerts-for-aws.git

****
HISTORY

About to commit first code 2020/06/22 11:42pm GMT -4 / EDT / Atlanta / New York.
