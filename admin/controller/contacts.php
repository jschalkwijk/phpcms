<?php

use CMS\Models\Controller\Controller;
use \CMS\Models\Contacts\Contact;

class Contacts extends Controller
{
    // import the remove/update/
    use \CMS\Models\Actions\UserActions;

    public function index($params = null)
    {
        $this->UserActions('contacts');
        $contacts = Contact::fetchAll('contacts', 0);
        $this->view(
            'Contacts',
            ['contacts/contacts.php'],
            $params,
            [
                'contacts' => $contacts,
                'trashed' => 0,
                'js' => [JS . 'checkAll.js']
            ]
        );
    }

    public function deletedContacts($params = null)
    {
        $this->UserActions('contacts');
        $contacts = Contact::fetchAll('contacts', 1);
        $this->view(
            'Contacts',
            ['contacts/contacts.php'],
            $params,
            [
                'contacts' => $contacts,
                'trashed' => 1,
                'js' => [JS . 'checkAll.js']
            ]
        );
    }

    public function addContact($params = null)
    {
        if (isset($_POST['submit'])) {
            $contact = new Contact($_POST['first_name'], $_POST['last_name'], $_POST['phone_1'], $_POST['phone_2'], $_POST['email_1'], $_POST['email_2'],
                $_POST['dob'], $_POST['street'], $_POST['street_num'], $_POST['street_num_add'], $_POST['zip'], $_POST['notes']);
            $add = $contact->addContact();
            $this->view(
                'Add contact',
                ['contacts/add-edit-contact.php'],
                $params,
                [
                    'output_form' => $add['output_form'],
                    'contact' => $contact,
                    'errors' => $add['errors'],
                    'messages' => $add['messages']
                ]
            );
        } else {
            $contact = new Contact(null, null, null, null, null, null, null, null, null, null, null, null);
            $this->view(
                'Add contact',
                ['contacts/add-edit-contact.php'],
                $params,
                ['contact' => $contact]
            );
        }
    }

    public function editContact($params = null)
    {
        if (isset($_POST['submit_file']) || !empty($_FILES['files']['name'][0])) {
            $file_dest = 'files/users/' . $_SESSION['username'] . '/';
            $thumb_dest = 'files/thumbs/users/' . $_SESSION['username'] . '/';
            Contact::addProfileIMG($file_dest, $thumb_dest, $params);
        }

        if (isset($_POST['submit'])) {
            $contact = new Contact($_POST['first_name'], $_POST['last_name'], $_POST['phone_1'], $_POST['phone_2'], $_POST['email_1'], $_POST['email_2'],
                $_POST['dob'], $_POST['street'], $_POST['street_num'], $_POST['street_num_add'], $_POST['zip'], $_POST['notes']);
            $add = $contact->editContact($_POST['id']);
            $this->view(
                'Edit',
                ['contacts/add-edit-contact.php'],
                $params,
                ['output_form' => $add['output_form'],
                    'contact' => $contact,
                    'errors' => $add['errors'],
                    'messages' => $add['messages']
                ]
            );
        } else {
            $contact = Contact::fetchSingle('contacts', $params[0]);;
            $this->view(
                'Add contact',
                ['contacts/add-edit-contact.php'],
                $params,
                ['contact' => $contact]
            );
        }
    }

    public function info($params = null)
    {
        $this->UserActions('contacts');
        $contact = Contact::fetchSingle('contacts', $params[0]);;
        $this->view(
            'Add contact',
            ['contacts/view-contact.php'],
            $params,
            ['contact' => $contact]
        );
    }
}

?>