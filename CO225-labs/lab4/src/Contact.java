public class Contact{

    private String firstName;
    private String lastName;
    private String telephone;

    // Constructor --------------------------------------------------------------------------------------------
    public Contact(String firstName, String lastName, String telephone) {
        this.firstName = firstName.replace(" ", "");
        this.lastName = lastName.replace(" ", "");
        //this.telephone = telephone.replace(" ", "");

        // Better to use following if there exists multiple phone numbers for the user
        this.telephone = telephone.replace(" ", "").replace(":::", ", ");
    }

    // Object related functions -------------------------------------------------------------------------------
    @Override
    public String toString() {
        return firstName + " " + lastName + " \t: " + getTelephone();
    }

    public Boolean compareTo(Contact c) {
        return (firstName.compareTo(c.firstName) == 0) && (lastName.compareTo(c.lastName) == 0);
    }

    // Searching Functions ------------------------------------------------------------------------------------
    public Boolean matchFirstName(String name) {
        return (name.toLowerCase().compareTo(this.firstName.toLowerCase()) == 0);
    }
    public Boolean matchLastName(String name) {
        return (name.toLowerCase().compareTo(this.lastName.toLowerCase()) == 0);
    }
    public Boolean isSuggestFirstName(String name) {
        // Add suggested results too
        if (this.firstName.length() > name.length()) {
            // Substring equal to given String's length
            String thisName = this.firstName.substring(0, name.length()).toLowerCase();
            return (name.toLowerCase().compareTo(thisName) == 0);
        }
        return false;
    }
    public Boolean isSuggestLastName(String name) {
        // Add suggested results too
        if (this.lastName.length() > name.length()) {
            // Substring equal to given String's length
            String thisName = this.lastName.substring(0, name.length()).toLowerCase();
            return (name.toLowerCase().compareTo(thisName) == 0);
        }
        return false;
    }

    // Formatting ---------------------------------------------------------------------------------------------
    private String getTelephone() {
        // Change the phone number to the standard format
        if (telephone.length()> 0 && telephone.substring(0, 3).compareTo("+94") == 0) {
            return telephone;
        } else {
            return "+94" + telephone;
        }
    }
}
