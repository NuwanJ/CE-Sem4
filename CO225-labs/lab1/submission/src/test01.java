import java.util.Scanner;

public class test01 {

    public static void main(String[] args) {

        String message = "Hello world";
        System.out.println(message);

        int day = 5;
        System.out.println("Today is Friday, " + day + ".");

        Scanner sc = new Scanner(System.in);

        /*String name = sc.next();
        int amount = sc.nextInt();

        System.out.println("Name is "+ name + " and amount is " + amount + ".");*/

        day = 7;
        System.out.println("Heres todays date:" + "Nov " + day + ", " + "" + "" + "" +
                "2010");

        /*int num1, num2;

        num1 = sc.nextInt();
        num2 = sc.nextInt();

        System.out.println("num1: " + num1 + " num2: " + num2);*/

        int pop = sc.nextInt();
        String preName = sc.nextLine();
        preName = sc.nextLine();
        String preAddr = sc.nextLine();

        System.out.println("\n>>" +  preName + " " + preAddr);



    }
}
