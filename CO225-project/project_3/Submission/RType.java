import java.util.Arrays;

public class RType {
    int rs;
    int rt;
    int rd;
    int shamt;
    int func;

    public RType(int[] instruction) {
        int[] rsBinary = Arrays.copyOfRange(instruction, 6, 11);
        int[] rtBinary = Arrays.copyOfRange(instruction, 11, 16);
        int[] rdBinary = Arrays.copyOfRange(instruction, 16, 21);
        int[] shamtBinary = Arrays.copyOfRange(instruction, 21, 26);
        int[] funcBinary = Arrays.copyOfRange(instruction, 26, 32);

        this.rs = InstructionMemory.binaryToDecimal(rsBinary);
        this.rt = InstructionMemory.binaryToDecimal(rtBinary);
        this.rd = InstructionMemory.binaryToDecimal(rdBinary);
        this.shamt = InstructionMemory.binaryToDecimal(shamtBinary);
        this.func = InstructionMemory.binaryToDecimal(funcBinary);

        int rsVal = RegisterFile.getData(this.rs);
        int rtVal = RegisterFile.getData(this.rt);
        int select = 0;
        switch (func) {
            case 32:
                select = 0;
                break;
            case 34:
                select = 1;
                break;
            case 24:
                select = 2;
                break;
            case 26:
                select = 3;
                break;
            case 36:
                select = 4;
                break;
            case 37:
                select = 5;
                break;
            case 39:
                select = 6;
                break;
            case 38:
                select = 7;
                break;
            case 2:
                select = 8;
                break;
            case 0:
                select = 9;
                break;
            case 42:
                select = 10;
                break;
            case 12:
                select = 11;
                break;
            default:
                System.out.println("Error in R-Type Instruction");
        }
        int rdVal = ALU.getResult(rsVal, rtVal, select);
        RegisterFile.setData(this.rd, rdVal);

    }

}
