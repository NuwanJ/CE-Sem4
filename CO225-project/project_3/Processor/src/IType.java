import java.util.Arrays;

public class IType {
    int opCode;
    int rs;
    int rt;
    int imm;

    public IType(int[] instruction) {
        int[] opCodeBinary = Arrays.copyOfRange(instruction, 0, 6);
        int[] rsBinary = Arrays.copyOfRange(instruction, 6, 11);
        int[] rtBinary = Arrays.copyOfRange(instruction, 11, 16);
        int[] immBinary = Arrays.copyOfRange(instruction, 16, 32);

        this.opCode = InstructionMemory.binaryToDecimal(opCodeBinary);
        this.rs = InstructionMemory.binaryToDecimal(rsBinary);
        this.rt = InstructionMemory.binaryToDecimal(rtBinary);
        this.imm = InstructionMemory.binaryToDecimal(immBinary);

        //new ALU(opCode,rs,rt,imm);
        int rsVal = RegisterFile.getData(rs);
        int rtVal = 0;
        int addr = 0;
        switch (opCode) {
            case 8:
                rtVal = ALU.getResult(rsVal, this.imm, 0);
                RegisterFile.setData(rt, rtVal);
                break;
            case 12:
                rtVal = ALU.getResult(rsVal, this.imm, 1);
                RegisterFile.setData(rt, rtVal);
                break;
            case 4:
                rtVal = RegisterFile.getData(rt);
                if (ALU.getResult(rsVal, rtVal, 12) == 1) {
                    InstructionMemory.pc = this.imm - 1;
                }
                break;
            case 7:
                rtVal = RegisterFile.getData(rt);
                if (ALU.getResult(rsVal, rtVal, 13) == 1) {
                    InstructionMemory.pc = this.imm - 1;
                }
                break;
            case 35:
                addr = ALU.getResult(rsVal, this.imm, 0);
                rtVal = DataMemory.getData(addr);
                RegisterFile.setData(rt, rtVal);
                break;
            case 43:
                rtVal = RegisterFile.getData(rt);
                addr = ALU.getResult(rsVal, this.imm, 0);
                DataMemory.setData(addr, rtVal);
                break;
            default:
                System.out.println("Error I-Type in Instruction");
        }
    }

}
