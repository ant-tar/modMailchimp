
modMailchimp is no longer being maintained or supported. 

Looking for developers to take over!



== modMailchimp ==

modMailchimp is a MODx package that lets you easily place a subscribe form anywhere on your website for any list you want.

=== Installation ===

Grab the latest package from the Downloads page and upload to /path/to/modx/core/packages

Once uploaded, login to the Manager and go to System > Package Management.
From the Download Extras dropdown, choose Search Locally for Packages. Install the package when it finds it (you'll need your MailChimp API key) and that's it!

=== Basic Usage ===

The basic snippet call only needs a listId:
{{{
[[!modMailchimp?listId=`3363a41ae0`]]
}}}
The easiest way to get a subscribe form up and running on your site is to go on the modMailchimp custom manager page (you'll find it in the Components menu). Find the list you want to use and copy the snippet code. Now all you have to do is paste it wherever you want the form to display.

To generate a form that will unsubscribe an email address, just add the unsubscribe action:
{{{
[[!modMailchimp?listId=`3363a41ae0` &action=`unsubscribe`]]
}}}

=== Advanced Usage ===

The modMailchimp snippet is very flexible. If you want to dive straight in, have a look at the [[Snippet Parameters]], otherwise check out some of the examples below:

=== Changelog ===
Look in /core/components/modmailchimp/docs/changelog.txt
