modMailchimp Documentation


Description:
modMailchimp is an extra for Modx 2.2.+ that will enable you to very quickly and easily add Mailchimp subscription and unsubscription functionality to your modx site. 

Features include:
- subscribe & unsubscribe from multiple lists
- override mailchimp api key [use a different mailchimp account]
- ability to prevent content editors from overridng the api key
- recaptcha support for unsubscriptions
- a handy snippet to 'list' your lists in the event you want to allow users to select from a choice of lists.
- ability to output lists of lists as different form controls [check box, radio button, select control[default]]
- custom subscription forms 
- merge field support
- ist subscription code copy & paste from manager page
- customize success & error messages 
- optional redirect to custom success and error resources
- lexicon support 


What's coming?
- ssl support for reCAPTCHA
- interst group support
- formIt hook option


Support & License:
You may submit any support requests, bug reports or feature requests at our github repository: http://github.com/BigBlockStudios/
modMailchimp is a free extra published under the gnu public license, no warranty is given or implied. 


Where to get:
You can download the transport and/or source from http://github.com/BigBlockStudios/
modMailchimp is also available via the modx extra repositoy [use your modx package manager to download & install]


MailChimp & reCAPTCHA
A mailchimp account [you will need an api key & at least one list set up] is required to use this extra, you can learn more & sign up for a free account at http://www.mailchimp.com 
A reCAPTCHA account is not required but VERY strongly recommended as it provides some security for your unsubscription forms. [and happens to be the default]You will need both your public and private reCAPTCHA keys in order to use reCAPTCHA support. Learn more about reCAPTCHA at http;//recaptcha.google.smething.or.other [just google it]


Installation:
Install modMailchimp using the package manager [available in the modx repository] within modx OR you can download the latest transport zip file from our github address. To install a downloaded transport file; upload the zip file to you /core/packages folder in your modx site, go into your package manager and click "search for files locally" ~ modMailchimp should appear in your extras list. To install from the modx repository, go into your package management, click get extras, search for 'modMailchimp', click download & install normally.

During installation you will be prompted for your Mailchimp api key, recaptcha public & private key and if you want to allow content editors [anyone without access t modify system settings] to override the api key in the snippet call. None of that is manditory at this time, but you will require a mailchimp api key to use the extra. If you choose to fill in these options later, you can access the options from the system settings menu under the modMailchimp sction.  



Snippets:

[[modMailchimp?]]
&listId {default:FALSE} The unique mailchimp list id
&apiKey {default:NULL} your Mailchimp api key
&action {default:subscribe} the action to perform [subscribe or unsubscribe]
&mergeTags {default:EMAIL} comma separated list of merge tags to include in the form
&successId {default:NULL} if specified the resource to redirect to on a successful submission
&failureId {default:NULL} if speciied the resource to redirect to on submission failure.
&placeholder {default:NULL} placeholder to post error/success messages to
&subscribeTpl {default:mmc_subscribe} if specified the chunk to use as the subscription form
&unsubscribeTpl {default:mmc_unsubscribe} if specified the chunk to use as the unsubscription form
&rowTpl {default:mmc_row} if specified the chunk to use for each row in the form
&formName {default:mailchimp} required if multiple forms are used in a single resource [names must be unique]
&recaptcha {default:true} use recaptcha or not [default = true on unsubscribe, default = false on subscribe forms.]
&listLists {default:NULL} include a form control to allow list selection
&listLabel {default:'Which List?'} the label to use for the list form control
&listControl {default:select} the type of form control[s] to output [radio|select]

modMailchimp use:
Placing the [[modMailchimp? &listId=`listId`]] tag in a resource will by default display a subscription form for the list id specified using the default form chunk. Upon submission mailchimp success or error messages are returned to the same page using the default chunk unless success and/or failure attributes are specified.
The simplest [and fastest] way to use modMailchimp is to go to the custom manager page [under componenets], copy the code there into the resource where you want the subscription form to appear, the only required attribute is the listId.

listId: the listId attribute is required, you may, however, override it by using a listId form field in a custom subscribeTpl or by using the listLists attribute, which will add a listId form control [select|radio or checkbox] to your subscribeTpl.

apiKey, You can override the default apiKey set in the system settings [if the allow override setting is true] if you need to use a different mailchimp account. 

action: not required, specifying 'unsubscribe' will instruct the form to try to unsubscribe the email address submitted.

mergeTags: a comma separated list of merge tags to include in the subscription form. modMailchimp tag will generate form controls for each merge tag you specify, please note that the merge field aliases [ex: MERGE1, MERGE2] will not work, you need to specify the name of the field, additionally, merge fields are listed in the order they are found in mailchimp, not the order you specify in the tag attribute. Merge tags are case sensitive.

To redirect a user to a different resource on form success or failure, just specify the successId & failureId attributes [specify the resource id of the resource you want to redirect to], in these resources you can either use static text for your messages or you can call the [[modMailchimpMessages]] to retrieve the status message that Mailchimp returns. 

The different Tpl attributes let you specify different chunks to use in place of the defaults for subscibe, unsubscribe & form row chunks. [see the section on chunks below for more information

If you have multiple list subscriptions in a single resource, you will need to specify a unique name for each form [formName] so that modMailchimp knows which form to process! 



[[modMailchimpMessenger?]]
&type {default:NULL} The message type to output [success or error]

modMailchimpMessenger Use:
Simply place this tag in a resource that you are redirecting users to & specify whether it is a success or error message you are expecting. i.e. [[modMailchimpMessenger? &type=`error`]]



[[modMailchimpLists?]]
&apiKey {default:NULL} your mailchimp api key
&lists {default:NULL} comma separated list of mailchimp list ids
&control {default:select} the type of form control[s] to display the lists [checkbox|radio|select]
&rowTpl {default:mmc_row} Name of the chunnk to use for each list row. 
&label {default:'Which list?'} the label to use for the lists label.

modMailchimpLists Use:
This tag is useful for placing in custom forms when you want a user to be able to select from a group of lists, simply create your custom form & place the tag where you want the list selection to appear. 
By default modMailchimpLists will display all lists in a given account, if you want to limit or exclude certain lists, just specify which lists to show with the &lists attribute.


Chunks:
modMailchimp comes with several default chunks for displaying the subscription forms and various message output, these are great to get you started, but you should create your own chunks if you want to customize them. Do not customize the default chunks, they will be overwritten during future updates. [in fact, you should always reate new chunks for any package just for this reason!]

mmc_unsubscribe chunk:
<h3>Unsubscribe</h3>
    <p>[[+errorCode]] [[+errorMessage]]</p>
    <p>[[+success]]</p>
    <form action="[[~[[*id]]]]" method="post">
        [[+fields]]
    <input type="submit" name="mmc_unsubscribe" value="go" />
</form>


mmc_subscribe chunk:
<h3>Subscribe</h3>
	<p>[[+errorCode]] [[+errorMessage]]</p>
	<p>[[+success]]</p>
	<form action="[[~[[*id]]]]" method="post">
		[[+fields]]
	<input type="submit" name="mmc_subscribe" value="go" />
</form>

Both subscribe and unsubscribe may use these placeholders:
[[+errorCode]]: the error code returned from mailchimp
[[+errorMessage]]: the error message 
[[+success]]: the success message
[[+fields]]: the form controls, uses the mmc_row to display form fields


mmc_row chunk:
<p>
	<label for="[[+tag]]"><strong>[[+name]]</strong></label>
	[[+input]]
</p>
Placeholders;
[[+tag]]: will output the id of the form control being generated  
[[+name]]: will use the name returned from mailchimp for the form control
[[+input]]: will output the form control





Usage & Examples:
The simplest way to use modMailchimp is to copy the code from the custom manager page [Components->modMailchimp] for the list you want into a resource & save the resource, it will work right out of the box! 


If you want to get a little more exciting: 

[[!modMailchimp? 
	&apikey=`your-api-key`	
	&listId=`your-list-id` 
	&successId=`2` 
	&failureId=`3` 
	&recaptcha=`1` 
	&mergeTags=`FNAME,LNAME,EMAIL,M6,M5,M4`
	]]
This tag will:
- use a different Mailchimp account than you specified during install
- use resources 2 & 3 for error & success pages
- force a reCAPTCHA
- display some merge fields 


To use a custom form with a list of lists option:

[[!modMailchimp? 
	&apikey=`150cd0eb2ff74cabd2d43d66031b6d87-us4`	
	&listId=`38049161ec` 
	&successId=`2` 
	&failureId=`3` 
	&subscribeTpl=`custom_mmc_subscribe`
	&formName=`customFrm`
	]]
This will do pretty much the same as the example above with the addition of using this custom form, note that since we are passing the merge fields, we no longer have to specify them in our tag:

<form action="[[~[[*id]]]]" method="post" name="[[+formName]]" >

	<p>
		<label for="EMAIL"><strong>Email Address</strong></label>
		<input type="text" name="EMAIL" value="" />
	</p>
	<p>
		<label for="FNAME"><strong>First Name</strong></label>
		<input type="text" name="mmc_mergevars[FNAME]" value="" />
	</p>
	<p>
		<label for="LNAME"><strong>Last Name</strong></label>
		<input type="text" name="mmc_mergevars[LNAME]" value="" />
	</p>
	<p>
		<label for="M4"><strong>MM04</strong></label>
		<input type="text" name="mmc_mergevars[M4]" value="" />
	</p>
	<p>
		<label for="M5"><strong>MM05</strong></label>
		<input type="text" name="mmc_mergevars[M5]" value="" />
	</p>
	<p>
		<label for="M6"><strong>MM06</strong></label>
		<input type="text" name="mmc_mergevars[M6]" value="" />
	</p>
	
	[[modMailchimpLists? &control=`radio` &label=`Available Lists:`]]
	
	<input type="submit" name="mmc_subscribe" value="go" />

</form>

Note the use of [[+formName]] to output the form name we passed in the tag call, also notice how the merge tag controls are named. We are doing it like this so that in the future if you only want to pass certain fileds to Mailchimp & other fields to another extra or process [i.e. formit or user profiles] the mailchimp plugin can recognize which it is supposed to pass. The value in the square braces is the name [not field label!] of the merge tag in your Mailchimp account. i.e. name="mmc_mergevars[-your-merge-tag-name-]"
th modMailchimpLists will output a set of radio controls for all available lists with the label 'Available Lists'

