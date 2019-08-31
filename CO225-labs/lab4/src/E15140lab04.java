/* -----------------------------------------------------------------
	CO225 Lab04

	Author      : 	Jaliyagoda A.J.N.M. (E/15/140)
	LastUpdate  :	2018/12/06
----------------------------------------------------------------- */

import java.util.Scanner;

public class E15140lab04 {

    public static void main(String[] agrs) {

        //String filePath = "C:/java/myContacts.csv";
        String filePath = "myContacts.csv";
        ContactBook cBook = new ContactBook(filePath); // Optional argument : ContactBook.csvFormat.GOOGLE | ContactBook.csvFormat.DEFAULT
        Scanner sc = new Scanner(System.in);

        // Input format:  filename [f | L]:[String]

        System.out.println("Please enter the name as one of following formats:");
        System.out.println("\tF:FirstName or L:LastName");
        System.out.print("Enter your opinion: ");

        String[] inputs = sc.next().split(":");

        if (inputs.length != 2) {
            // Exit from the program if argument isn't in right format
            System.out.println("Invalid input !");
            System.exit(-1);

        } else if (inputs[1].compareTo("") == 0) {
            // Exit from the program if filter text is empty
            System.out.println("Filter can't be empty !");
            System.exit(-1);

        } else if (inputs[0].compareTo("F") != 0 && inputs[0].compareTo("L") != 0) {
            // Exit from the program if filer character is other than L or F
            System.out.println("Invalid filter character !");
            System.exit(-1);

        } else {
            //System.out.println("\nFilter > " + agrs[0]);
            System.out.println("-----------------------------------");

            if (inputs[0].compareTo("F") == 0) {
                cBook.printByFirstName(inputs[1]);
            } else {
                cBook.printByLastName(inputs[1]);
            }

            //cBook.printAllContacts();
        }
    }
}
