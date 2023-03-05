
export namespace Dtos {
  export type RegistrationData = {
    name: string
    email: string
    password: string
    confirmPassword: string
  }

  export class SuccessRegistration {
    constructor(
      readonly message: string
    ){}
  }
}
