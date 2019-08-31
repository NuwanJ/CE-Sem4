import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
import java.io.IOException;
import java.util.*;

public class ContactBook {

    public enum csvFormat {
        DEFAULT,
        GOOGLE
    }

    private ArrayList<Contact> contactList = new ArrayList<>();

    // Constructor Method (s) ---------------------------------------------------------------------------------
    public ContactBook(String filePath) {
        readCSV(filePath);
    }

    public ContactBook(String filePath, csvFormat source) {
        if (source == csvFormat.GOOGLE) {
            readGoogleCSV(filePath);
        } else {
            readCSV(filePath);
        }
    }

    // Load contact details from the given file ---------------------------------------------------------------
    private void readCSV(String filePath) {
        BufferedReader reader = null;

        try {
            reader = new BufferedReader(new FileReader(filePath));

            String[] values;
            String firstName, lastName, email, tele;

            for (String line = reader.readLine(); line != null; line = reader.readLine()) {
                values = line.split(",");

                firstName = values[0];
                lastName = values[1];
                tele = values[2];

                contactList.add(new Contact(firstName, lastName, tele));

            }
            reader.close();

        } catch (IOException e) {
            e.printStackTrace();
            System.exit(-1);

        } catch (ArrayIndexOutOfBoundsException e) {
            System.out.println("Malformed CSV file");
            e.printStackTrace();
        }
    }

    // Print all contacts on the address book -----------------------------------------------------------------

    public void printAllContacts() {

        for (Contact c : contactList) {
            System.out.println(c.toString());
        }
    }

    // List contacts ------------------------------------------------------------------------------------------

    public List<Contact> findByFirstName(String fName) {

        List<Contact> con = new LinkedList<>();

        for (Contact c : contactList) {
            if (c.matchFirstName(fName)) {
                System.out.println(c.toString());
                con.add(c);
            }
        }

        return con;
    }

    public List<Contact> findByLastName(String lName) {

        List<Contact> cont = new LinkedList<>();

        for (Contact c : contactList) {
            if (c.matchLastName(lName)) {
                System.out.println(c.toString());
                cont.add(c);

            }/* else if (c.isSuggestLastName(lName)) {
                System.out.println(c.toString());
                con.add(c);
            }*/
        }

        return cont;
    }

    // Print contacts -----------------------------------------------------------------------------------------

    public void printByFirstName(String name) {

        List<Contact> contactList = this.findByFirstName(name);

        if (contactList.size() == 0) {
            System.out.println("0 results");
        } else {
            for (Contact c : contactList) {
                c.toString();
            }
        }
    }

    public void printByLastName(String name) {

        List<Contact> contactList = this.findByLastName(name);

        if (contactList.size() == 0) {
            System.out.println("0 results");
        } else {
            for (Contact c : contactList) {
                c.toString();
            }
        }
    }

    // Read from Google CSV -----------------------------------------------------------------------------------

    public void readGoogleCSV(String dirname) {

        File f = new File(dirname);
        try {
            BufferedReader br = new BufferedReader(new FileReader(f));
            String st;
            st = br.readLine(); // Read the header

            while ((st = br.readLine()) != null) {
                String[] values = st.split(",");

                if (values.length > 34) {
                    String firstName = values[1];
                    String lastName = values[3];
                    String tele = values[34];
                    contactList.add(new Contact(firstName, lastName, tele));
                }
            }
        } catch (Exception e) {
            System.out.println("Error! " + e.toString());
            e.printStackTrace();
        }
    }
}
