import java.util.Arrays;

public class JType {
    int opCode;
    int targetAddr;

    public JType(int[] instruction) {
        int[] opCodeBinary = Arrays.copyOfRange(instruction, 0, 6);
        int[] targetAddrBinary = Arrays.copyOfRange(instruction, 6, 32);

        this.opCode = InstructionMemory.binaryToDecimal(opCodeBinary);
        this.targetAddr = InstructionMemory.binaryToDecimal(targetAddrBinary);

        switch (opCode) {
            case 2:
                InstructionMemory.pc = targetAddr - 1;
                break;
            case 3:
                RegisterFile.setReturnAddr(InstructionMemory.pc);
                InstructionMemory.pc = targetAddr - 1;
                break;
            case 16:
                int addr = RegisterFile.getData(targetAddr);
                InstructionMemory.pc = addr - 1;
                break;
            default:
                System.out.println("Error in J-Type Instruction");

        }
    }

}
