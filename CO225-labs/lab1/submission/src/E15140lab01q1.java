import java.util.Scanner;

/*
    A number is special, if it is exactly divisible by 15.
    A number is big, if it is greater than 999.
    A number is weird, if it is exactly divisible by 5 and 6 but not 18.
    A number is scary if it is big or weird.

    Write a Java program to test a given number. For example:
        Enter a number: 450
            450 is special but not scary.

        Enter a number: 750
            750 is special, weird and scary but not big.
*/

public class E15140lab01q1 {
    public static void main(String[] args) {

        Scanner sc = new Scanner(System.in);
        //int t = sc.nextInt();
        //for (int j = 0; j < t; j++) {

        int n;
        String posResult = "", negResult = "";

        System.out.print("Enter a number: ");
        n = sc.nextInt();

        boolean special = (n % 15 == 0);
        boolean big = (n > 999);
        boolean weird = (n % 5 == 0) && (n % 6 == 0) && !(n % 18 == 0);

        if (special) {                  // Special condition should show only if it is positive
            posResult = posResult + " special,";
        }

        if (big && weird) {             // both big and weird
            posResult = posResult + " big, weird, scary,";

        } else if (!big && !weird) {    // both not big and weird
            negResult = negResult + " scary,";

        } else {
            if (big) {
                posResult = posResult + " big,";
            } else {
                negResult = negResult + " big,";
            }
            if (weird) {
                posResult = posResult + " weird,";
            } else {
                negResult = negResult + " weird,";
            }
            posResult = posResult + " scary,";
        }

        if ((posResult.length() != 0) && (negResult.length() == 0)) {
            // Only positive
            posResult = posResult.substring(0, posResult.length() - 1); // remove last ','; a short hand trick to make it simple
            System.out.println(n + " is" + posResult + ".");

        } else if ((posResult.length() == 0) && (negResult.length() != 0)) {
            // Only negative
            negResult = negResult.substring(0, negResult.length() - 1); // remove last ','
            System.out.println(n + " is not" + negResult + ".");

        } else {
            // Both positive and negative
            negResult = negResult.substring(0, negResult.length() - 1); // remove last ','
            System.out.println(n + " is" + posResult + " but not" + negResult + ".");
        }
        //}
    }
}

/* ****************************************************************************
// Alternative Code
		Scanner sc = new Scanner(System.in);
        int n, count = 0;
        String result = "";

        //String[] expression = {"special", "big", "weird", "scary"};

        System.out.print("Enter a number: ");
        n = sc.nextInt();

        if ((n % 15 == 0)) {
            count += 100;
        }
        if (n > 999) {
            count += 10;
        }
        if ((n % 5 == 0) && (n % 6 == 0) && !(n % 18 == 0)) {
            count += 1;
        }

        // special | big | weird  <scary>
        if (count == 0) {
            result = n + " is not special, big, weird, scary.";

        } else if (count == 100) {
            //result = n + " is special but not scary.";
            result = n + " is special, but not big, weird, scary.";

        } else if (count == 10) {
            //result = n + " is big and scary but not special.";
            result = n + " is big, scary but not weird.";

        } else if (count == 11) {
            result = n + " is big, weird, scary but not special.";

        } else if (count == 1) {
            //result = n + " is weird but not special, big and scary.";
            result = n + " is weird but not special, big, scary.";

        } else if (count == 101) {
            //result = n + " is special, weird and scary but not big.";
            result = n + " is special, weird, scary, but not big.";

        } else if (count == 110) {
            //result = n + " is special, big and scary but not weird.";
            result = n + " is special, big, scary, but not weird.";

        } else if (count == 111) {
            //result = n + " is special and scary.";
            result = n + " is special, big, weird, scary.";
        }
        //System.out.println(count);
        System.out.println(result);


**************************************************************************** */

