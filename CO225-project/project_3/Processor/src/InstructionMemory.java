import java.util.Arrays;
import java.util.HashMap;

public class InstructionMemory {
    // To store instructions
    private static HashMap<Integer, String> instructionMem = new HashMap<>();

    //program counter
    public static int pc = 0;

    //Convert binary numbers into decimal
    public static int binaryToDecimal(int[] binary) {
        int dec = 0;
        for (int i = binary.length - 1; i >= 0; --i) {
            dec += binary[i] * Math.pow(2, binary.length - 1 - i);
        }
        return dec;
    }

    //Decide whether a instruction is I type, J type or R type
    private void findInstructionType(String instruction) {

        int[] inst = new int[32];

        // Convert instruction to int[] array
        // TODO: This should implement to Binary array

    	for(int i=0;i<instruction.length();i++){
    		if (instruction.charAt(i)=='0'){
    			inst[i] = 0;
			}else{
				inst[i] = 1;
			}
		}

		int opCodeDec = binaryToDecimal(Arrays.copyOfRange(inst,0, 6));

        System.out.print("  >> "+ opCodeDec);

        if (opCodeDec == 0) {
            System.out.print("\t R-Type\t");
            System.out.print(" Rs:" + binaryToDecimal(Arrays.copyOfRange(inst,7, 11)));
            System.out.print(" Rt:" + binaryToDecimal(Arrays.copyOfRange(inst,11, 16)));
            System.out.print(" Rd:" + binaryToDecimal(Arrays.copyOfRange(inst,16, 21)));
            System.out.print(" sh:" + binaryToDecimal(Arrays.copyOfRange(inst,21, 26)));
            System.out.print(" func:" + binaryToDecimal(Arrays.copyOfRange(inst,26, 32)));

            new RType(inst);

        } else if (opCodeDec == 2 || opCodeDec == 3 || opCodeDec == 16) {
            System.out.print("\t J-Type");
            System.out.print(" Opcode:" + opCodeDec);
            System.out.print(" Target:" + binaryToDecimal(Arrays.copyOfRange(inst,7, 31)));

            new JType(inst);

        } else if (opCodeDec == 8 || opCodeDec == 12 || opCodeDec == 4|| opCodeDec == 7|| opCodeDec == 35|| opCodeDec == 43) {
            System.out.print("\t I-Type");
            System.out.print(" Opcode:" + opCodeDec);
            System.out.print(" Rs:" + binaryToDecimal(Arrays.copyOfRange(inst,7, 11)));
            System.out.print(" Rt:" + binaryToDecimal(Arrays.copyOfRange(inst,12, 16)));
            System.out.print(" Im:" + binaryToDecimal(Arrays.copyOfRange(inst,17, 32)));

            new IType(inst);
            
        }else{
            System.out.print("\t Unsupported");
        }
        System.out.println();
    }

    //Iterate through the instruction memory
    public void processerLoop() {


        while (pc <= 1000) {
            try {
                System.out.println(pc);
                String currentInstruction = instructionMem.get(pc);

                if (currentInstruction == null) {
                    break;
                } else {
                    findInstructionType(currentInstruction);
                }

            } catch (Exception e) {
                System.out.println("Error in Instruction Memory");
                e.printStackTrace();
            }
            ++pc;

        }
    }

    public void pushInstruction(int addr, String instruction) {
        instructionMem.put(addr, instruction);
    }

}
