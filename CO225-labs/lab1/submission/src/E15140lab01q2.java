import java.util.Scanner;

/*
    Although this generally works well, it doesnt generate good complements for grey colors that
    have all three components right around 128.

    To x this you will return an alternate complement for grey colors.

    If each component of a color and its corresponding component of the colors complement
    dier by 32 or less, then make the complement of each component by
    either adding 128 to a components value, or by subtracting 128 from a components value, whichever one
    results in a legal value.

    For example, the color 115,115,143 would have the complement 140, 140,112, but since
    each component of the complement would have been within 32 of the corresponding component of
    rgb, we return the alternate complement 243, 243, 15 instead.

    115 115 143 ->  243 243 15
    255 0 0     ->  0 255 255
 */

public class E15140lab01q2 {

    public static void main(String[] args) {
        Scanner sc = new Scanner(System.in);

        //int t = sc.nextInt();
        //for (int j = 0; j < t; j++) {

        int[] colors = new int[3];
        int[] compColors = new int[3];
        boolean grayFlag = true;

		System.out.print("Enter the color: ");

        for (int i = 0; i < 3; i++) {
            colors[i] = sc.nextInt();
            // TODO: Write validation statement, if required
        }

        // Determine is it a gray color or not
        for (int i = 0; i < 3; i++) {
            compColors[i] = 255 - colors[i];

            if (Math.abs(colors[i] - compColors[i]) > 32) {
                grayFlag = false;
                //break;
            }
        }

        // Special gray condition
        for (int i = 0; i < 3; i++) {
            if (grayFlag) {
                //if (Math.abs(colors[i] - compColors[i]) <= 32) {
                if (colors[i] >= 128) {
                    compColors[i] = colors[i] - 128;
                } else {
                    compColors[i] = colors[i] + 128;
                }
            }
        }
        
        System.out.println("The complement: " + compColors[0] + " " + compColors[1] + " " + compColors[2]);
        //}
    }
}
