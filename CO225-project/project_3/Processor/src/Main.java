import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;

public class Main {
    public static void main(String[] args) {

        if(args.length !=1){
            // No input arguments
            System.out.println("No enough arguments");
            System.exit(-1);

        }else {
            BufferedReader reader = null;

            try {
                reader = new BufferedReader(new FileReader(args[0]));
                int i=0;
                InstructionMemory im = new InstructionMemory();

                for (String line = reader.readLine(); line != null; line = reader.readLine()) {

                    // Read each line and add to instruction memory
                    if(!line.startsWith("//") && line.length()>0){

                        im.pushInstruction(i, line.replace("_", ""));
                        i++;
                    }
                }
                reader.close();

                im.processerLoop();

            } catch (IOException e) {
                e.printStackTrace();
                System.exit(-1);

            } catch (ArrayIndexOutOfBoundsException e) {
                System.out.println("Malformed file");
                e.printStackTrace();
                System.exit(-1);
            }
        }
    }
}
