import java.util.*;

public class Solution {

    public static void main(String[] args) {

        Scanner sc = new Scanner(System.in);
        int t = sc.nextInt();

        for (int j = 0; j < t; j++) {

            int[] colors = new int[3];
            int[] compColors = new int[3];
            boolean grayFlag = true;

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
        }
    }
}
