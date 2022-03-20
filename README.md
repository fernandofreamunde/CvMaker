# Welcome to Cv Maker

This project aims to create a simple API that allows the creation of a CV. 

The responsibility of the visual representation of the CV would be for a different application.

This app will just receive HTTP requests in order to Create, Read, Update and Delete: Contacts, Education, Work Experience and Skills.

For some context, I actually want this to be the backend for a different project that is private at the moment, you probably guessed it is my actual CV which I have written in React because... why not? xD 

So I am basing the requirements on that project. And this way you can use this on your CV and make a Frontend style for yourself with HTML and some JS...

## API documentation

You have a Swagger interface to look at after you boot the project on `<host address>/api` that is generated by API platform.

The database for this project is sqlite I want it to be a local thing for my own use, so no need for booting up docker or install services on your machine. 

### Entities purpose and how to use them

Since this is a simple API for my own needs, I am not adding a lot of complexity.

On a normal application I would be adding createdAt, updatedAt and deletedAt(where soft delete is applied) fields. 

#### Contact

Contacts are the entries for your personal links, think of LinkedIn, email, github... 

On my CV I also add the city where I am based.

Since these will be transformed into a piece of paper I don't really add links just strings.

Therefore the contacts Entity have the following structure:

- **id**: _int_ (on an actual api I would use a string for uuids)
- **name**: _string_ (name of the entry)
- **content**: _string_ (the actual links, handles, etc...)

#### Education

I only have one education entry but clever people may have more, so we add a table for it.

the entity fields are:

- **id**: _int_ (same as above ...)
- **schoolName**: _string_ (name of the education institution)
- **courseName**: _string_ (the name of the course studied)
- **graduationYear**: _string_ (the year the studies were completed)

#### WorkExperience

the entity fields are:

- **id**: _int_ (...)
- **companyName**: _string_ (name of the company)
- **role**: _string_ (the role played during this job)
- **startingDate**: _datetime_ (when the role started)
- **endingDate**: _datetime_ (if null it is an ongoing job)
- **description**: _text_ (description of the tasks performed in this job)

#### Skills

We may have different types of skills I am using an enum to list the possible values for the Type of skill, in the database this will ultimately be recorded as a string.

This entity is where I am saving experience with Languages(the spoken type too), Technologies and tools.

the entity fields are:

- **id**: _int_
- **type**: _string_
- **content**: _string_ 

---

This readme is being written before the code also works a bit like requirements for this API.

This is a personal project, for personal use, no need for Authentication/Authorization
