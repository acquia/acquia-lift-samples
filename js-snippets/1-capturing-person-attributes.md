# Capturing person attributes
Add the following code to your webpage:

    <script type="text/javascript">
    _tcaq.push( ['updatePerson',
    {'person_udf5':'attributedata1', 'person_udf6':'attributedata2', 'person_udf7': 'attributedata3' }
    ] );
    </script>
where person_udf is a user-defined field for a person and attributedata is the information to add to the person. You can define up to 50 fields for values.

Acquia Lift captures the fields and associates them with the website visitors information.

When using an attribute containing a date, the date must display in the following format:
`yyyy-MM-dd'T'HH:mm:ss.SSS'Z`

For example:
`2015-03-25T00:00:00.000Z`

###Example usage
The following example sets user-defined fields 5, 6, and 7 to firmographic values associated with the business the person works for—the company SMB, categorized as Retail, with 0-50 employees:

    <script type="text/javascript">
    _tcaq.push( ['updatePerson',
    {'person_udf5':'SMB', 'person_udf6':'Retail', 'person_udf7': '0-50 employees' }
    ] );
    </script>

##Note

Public _tcaq functions are only available after lift-capture.js is loaded on a page after personalization return. You will know lift-capture.js hasnt loaded on a page if you receive the following reference error:

`ReferenceError: _tcaq is not defined`
